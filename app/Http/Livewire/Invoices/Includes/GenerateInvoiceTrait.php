<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Events\NewInvoice;
use App\Models\Comprobante;
use App\Models\Detail;
use App\Models\Invoice;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait GenerateInvoiceTrait
{
    public $invoice;

    public function createDetails($invoice)
    {
        $gasto = 0;
        $gasto_service = 0;
        $venta=0;
        $venta_service=0;
        foreach ($this->details as $ind => $detail) {
            unset($this->details[$ind]['product_name']);
            unset($this->details[$ind]['unit_name']);
            unset($this->details[$ind]['id']);
            $detail['detailable_id'] = $invoice->id;
            $detail['detailable_type'] = Invoice::class;
            $taxes = empty($detail['taxes']) ? [] : $detail['taxes'];
                $detail['total'] = $detail['subtotal'] - $detail['discount'];
                $detail['user_id'] = 1;
            $det = Detail::create(Arr::except($detail, ['taxes','product_name','product_code','unit_name','unit_pivot_id']));
            if ($invoice->type != 'B00' && $invoice->type != 'B14') {
                $det->taxes()->sync($taxes);
                $det->taxtotal = $det->taxes->sum('rate') * $det->subtotal;
            }
            $det->save();
           
           
            $product = $det->product;
            if ($product->type=='Producto') {
                $venta += $det->subtotal-$det->discount;
            } else {
                $det->update([
                    'cost_service'=>$det->cost,
                    'cost'=>0,
                ]);
                $venta_service += $det->subtotal-$det->discount;
            }
            $gasto += $det->cant * $det->cost;
            $gasto_service += $det->cant * $det->cost_service;
            $this->restStock($detail['unit_pivot_id'], $detail['cant'], $product);
        }
        $invoice->update(['gasto' => $gasto]);
        $invoice->update(['venta' => $venta]);
        $invoice->update(['gasto_service' => $gasto_service]);
        $invoice->update(['venta_service' => $venta_service]);
        Log::info('Invoice created', ['invoice' => $invoice, 'gasto' => $gasto, 'venta' => $venta, 'gasto_service' => $gasto_service, 'venta_service' => $venta_service]);
    }
    public function setFromScan()
    {
        $scanned = explode('.', substr($this->scanned, 1), 4);
        $this->selProducto($scanned[0]);
        $this->form['product_id'] = $scanned[0];
        $this->form['unit_id'] = $scanned[1];
        $this->form['cant'] = $scanned[2];
        $this->form['cost'] = $scanned[3];
        $this->setProduct($this->form['product_id']);
        $this->updatedForm(13, 'unit_id');
        $this->tryAddItems();
    }

    public function trySendInvoice()
    {
        $condition = $this->condition != 'De Contado' && $this->condition != 'Contra Entrega'
         && array_sum(array_column($this->details, 'total')) > $this->client['limit'];

        if ($condition && !auth()->user()->hasPermissionTo('Autorizar')) {
            $this->authorize('El cliente ha superado su crédito', 'validateAuthorization','sendInvoice','data=null','Autorizar');
        } else {
            $this->sendInvoice();
        }
        $this->facturable=true;
    }

    public function sendInvoice()
    {
        $store=optional(auth()->user())->store?:Store::find(env('STORE_ID'));
        $place=optional(auth()->user())->place?:$store->places->first();
        $this->checkCompAmount($store);
        if (!count($this->details)) {
            return;
        }
        $total = array_sum(array_column($this->details, 'subtotal'));
        $user = auth()->user()?:User::first();
        $comp_id = null;
        if ($this->type != 'B00' ) {
            $comp_id = $this->comprobante_id;
            $comprobante = Comprobante::whereId($comp_id)->first();
            $comprobante->update([
                'status' => 'usado',
                'period'=>date('Ym'),
                'user_id'=>$user->id,
                'place_id'=>$user->place->id,
                'client_id'=>$this->client['id'],
            ]);
        }
        $invoice = $user->store->invoices()->create(
            [
                'day' => date('Y-m-d'),
                'seller_id' => $user->id,
                'condition' => $this->condition,
                'expires_at' => $this->vence,
                'contable_id' => $user->id,
                'number' => $this->number,
                'name' => $this->name,
                'rnc' => $this->rnc,
                'place_id' => $user->place->id,
                'store_id' => $user->store->id,
                'client_id' => $this->client['id'],
                'comprobante_id' => $comp_id,
                'status' => 'waiting',
                'type' => $this->type,
            ]
        );
        if($this->chasis){
            $this->chasis->update([
                'invoice_id'=>$invoice->id,
                'status'=>'Entregado',
            ]);
        }
        $this->createPayment($invoice);
        $this->createDetails($invoice);
        if ($invoice->type != 'B00' && $invoice->type != 'B14') {
            $this->createInvoiceTaxes($invoice);
        }
        event(new NewInvoice($invoice));
        $this->reset('form', 'details', 'producto', 'price', 'client', 'client_code', 'product_code', 'product_name', 'name');
        $this->invoice = $invoice->load('seller', 'contable', 'client.contact', 'details.product.units', 'details.taxes', 'details.unit', 'payment', 'store.image', 'payments.pdf', 'comprobante', 'pdf', 'place.preference');
        if (getPreference($place->id)->print_order=='yes') {
            $this->emit('printOrder', $this->invoice);
        }
        $dataFile = file_get_contents(storage_path('app/public/local/details.json'));
        $data = json_decode($dataFile, true) ?: [];
        $name=$invoice->name?:$invoice->client->name;
        unset($data[$this->localDetail]);
        file_put_contents(storage_path('app/public/local/details.json'), json_encode($data));
        if(getPreference($place->id)->instant=='yes'){
            $this->instant=true;
            $this->emit('modalOpened');
        }
        Cache::forget('place_invoices_with_trashed'.$place->id);
        $this->mount();
    }
    public function createPayment($invoice)
    {
        $subtotal = array_sum(array_column($this->details, 'subtotal'));
        $discount = array_sum(array_column($this->details, 'discount'));
        $tax = 0;
        if ($invoice->type != 'B00' && $invoice->type != 'B14') {
            $tax = array_sum(array_column($this->details, 'taxTotal'));
        }
        $total = $subtotal - $discount + $tax;
        if ($invoice->condition == "De Contado" || $invoice->condition=='Contra Entrega') {
            $forma = 'contado';
        } else {
            $forma = 'credito';
        }


        $data = [
            'ncf' => optional($invoice->comprobante)->ncf,
            'amount' => $subtotal,
            'discount' => $discount,
            'total' =>  $total,
            'tax' =>  $tax,
            'payed' => 0,
            'rest' =>  $total,
            'cambio' =>  0,
            'efectivo' => 0,
            'tarjeta' => 0,
            'transferencia' => 0,
            'forma' => $forma
        ];
        $invoice->payment()->save(setPayment($data));
        $invoice->client->payments()->save($invoice->payment);
    }
    public function restStock($pivotUnitId, $cant, $product)
    {
        if ($product->type == 'Producto') {
            $user = auth()->user();
            $unit = $user->place->units()->wherePivot('id', $pivotUnitId)->first();
            $unit->pivot->stock =floatVal(str_replace(',','',$unit->stock)) - $cant;
            $unit->pivot->save();
        }
    }
    public function verifyCredit($amount, $credit)
    {
        return !$amount > $credit;
    }
    public function createInvoiceTaxes($invoice)
    {
        $details = $invoice->details()->with('taxes')->get();
        foreach ($details as $detail) {
            foreach ($detail->taxes as $tax) {
                DB::table('invoice_taxes')->updateOrInsert(
                    [
                        'tax_id' => $tax->id,
                        'invoice_id' => $invoice->id
                    ],
                    [
                        'tax_id' => $tax->id,
                        'amount' => DB::raw('amount +' . $tax->rate * $detail->subtotal)
                    ]
                );
            }
        }
    }
    
}
