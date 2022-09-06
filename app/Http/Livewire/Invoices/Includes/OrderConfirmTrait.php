<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Contrato;
use App\Models\Cuota;
use App\Models\Invoice;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use NumberFormatter;
use Termwind\Components\Dd;

trait OrderConfirmTrait
{

    public $action, $invoice;
    public function tryPayInvoice()
    {
        $invoice = Invoice::whereId($this->form['id'])->first();
        $condition = ($this->form['condition'] == 'De Contado'|| $this->form['client']['id']==1) && $this->form['rest'] > 0;
        
        if ($condition && !auth()->user()->hasPermissionTo('Autorizar')) {
           
            $this->authorize('Fiar factura de contado o a Genérico', 'validateAuthorization','payInvoice','data=null','Autorizar');
        } else {
            $this->payInvoice();
        }
    }
    public function payInvoice()
    {
        $user=optional(auth())->user()?:User::find(1);
        $invoice = Invoice::find($this->form['id'])->load('seller',  'client', 'details.product.units', 'details.taxes', 'details.unit', 'payment.pdf', 'store.image', 'comprobante', 'pdf', 'place.preference');
       
        $this->validateData($invoice);
        if ($invoice->status !== 'waiting') {
            $this->emit('showAlert', 'Esta factura ya fue cobrada. Recargue la vista', 'warning');
            return;
        }
        if ($this->form['rest'] <= 0 && $this->form['status'] != 'entregado') {
           
            $this->form['condition'] = 'De Contado';
        } else {
           
        }
        $this->form['status'] = 'cerrada';
        $pagos = ['Efectivo' => $invoice->efectivo, 'Tarjeta' => $invoice->tarjeta, 'Transferencia' => $invoice->transferencia];
        $this->form['payway'] = array_search(max($pagos), $pagos);
        $payment = $invoice->payment;
        if ($invoice->image) {
            $payment->image()->create([
                'path' => $invoice->image->path
            ]);
        }
        $invoice->update(Arr::only($this->form, ['note', 'status', 'payway', 'contable_id']));
        $payment->update(Arr::only($this->form, ['efectivo', 'tarjeta', 'transferencia', 'payed', 'rest', 'cambio']));
        $invoice->update(['rest' => $payment->rest]);
           $user->payments()->save($payment);
        if ($payment->payed > 0) {
            setIncome($invoice, 'Ingreso por venta Factura Nº. ' . $invoice->number, $payment->payed);
        }
        if ($invoice->comprobante) {
            $this->setTaxes($invoice);
        }
        $this->setTransaction($invoice, $payment, $invoice->client);
        setPDFPath($invoice);

        $this->closeComprobante($invoice->comprobante, $invoice);
        $invoice = Invoice::whereId($this->form['id'])->with('seller', 'contable', 'client.contact', 'details.product.units', 'details.taxes', 'details.unit', 'payment', 'store.image', 'payments.pdf', 'comprobante', 'pdf', 'place.preference')->first();
        if($this->createCuota){
            $this->createCuota($invoice);
            $this->createContrato($invoice);
        }
        $this->emit('showAlert', 'Factura cobrada exitosamente', 'success');
        if ($user->hasPermissionTo('Imprimir Facturas'))
            $this->emit('changeInvoice', $invoice);
        return redirect()->route('invoices.show', $invoice);
        $this->emit('refreshLivewireDatatable');
    }
    public function createContrato($invoice){
        $contrato=new Contrato();
        $contrato->cuotas=$this->cuotas;
        $contrato->interes=$this->interes;
        $contrato->client_id=$invoice->client_id;
        $contrato->place_id=$invoice->place_id;
        $contrato->invoice_id=$invoice->id;
        $contrato->tipo=$this->tipo;
        $contrato->modelo=$this->modelo;
        $contrato->marca=$this->marca;
        $contrato->color=$this->color;
        $contrato->chasis=$this->chasis;
        $contrato->year=$this->year;
        $contrato->placa=$this->placa;
        $contrato->save();
        $this->saveContratoPDF($contrato);
    }
    public function saveContratoPDF($contrato){
        $date = date_create($contrato->invoice->day);
            $meses = ['January' => 'ENERO', 'february' => 'FEBRERO', 'March' => 'MARZO', 'May' => 'MAYO', 'June' => 'JUNIO','July' => 'JULIO', 'August' => 'AGOSTO', 'September' => 'SEPTIEMBRE', 'October' => 'OCTUBRE', 'November' => 'NOVIEMBRE', 'December' => 'DICIEMBRE'];
            $f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
            $año = $f->format(date_format($date, 'Y'));
            $dia = $f->format(date_format($date, 'd'));
            $cuota=$contrato->invoice->cuotas()->first();
            $cuota->fecha=date_create($cuota->fecha);
            $store=optional(auth()->user())->store?:Store::find(env('STORE_ID'));
            $user=$store->users()->where('users.id','!=',1)->first();
        $data = [
            'invoice' => $contrato->invoice,
            'client' => $contrato->client,
            'store' => Store::find(env('STORE_ID')),
            'dia' => $dia,
            'mes' => $meses[date_format($date, 'F')],
            'año' => $año,
            'cuotaDia'=>$f->format(date_format($cuota->fecha, 'd')),
            'cuotaMes'=>$meses[date_format($cuota->fecha, 'F')],
            'cuotaAño'=>$f->format(date_format($cuota->fecha, 'Y')),
            'date' => $date,
            'user'=>$user,
            'contrato' => $contrato,
            'cuota' => $cuota,
            'contact' => $contrato->client->contact,
            'f'=> $f,
        ];
        $PDF = App::make('dompdf.wrapper');
        $pdf = $PDF->setOptions([
            'logOutputFile' => null,
            'isRemoteEnabled' => true
            ])->loadView('pages.clients.contrato-pdf', $data);
        //delete file if exists
        $name='files'.env('STORE_ID').'/contratos/contrato'.$contrato->id.'.pdf';
        if (Storage::disk('digitalocean')->exists($name)) {
            Storage::disk('digitalocean')->delete($name);
        }
        Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
            $url= Storage::url($name);
            $contrato->client->pdfs()->create([
                'pathLetter'=>$url,
                'reference_id'=>$contrato->invoice->id,
                'note' => 'Contrato Nº. ' . $contrato->id,
            ]);
       
    }
    public function createCuota($invoice){
        $fecha=Carbon::createFromDate($invoice->day);
        $amortizacion=amortizar($invoice->rest, $this->interes, $this->cuotas);

        foreach ($amortizacion->pagos as $pagos) {
            $new_fecha=sumarfecha($fecha, $this->periodo);
            $cuota= new Cuota();
            $cuota->fecha=$new_fecha;
            $cuota->periodo=$this->periodo;
            $cuota->invoice_id=$invoice->id;
            $cuota->client_id=$invoice->client_id;
            $cuota->saldo=$pagos->saldo;
            $cuota->interes=$pagos->interes;
            $cuota->capital=$pagos->capital;
            $cuota->debe=$pagos->debe;
            $cuota->restante=$pagos->restante;
            $cuota->save();
            $fecha=$new_fecha;
        }
        $place=$invoice->place;
        $client=$invoice->client;
            setTransaction('Reg. Interés de venta', $invoice->number, $amortizacion->suma-$invoice->rest, 
            $client->contable()->first(), $place->findCount('402-02'), 'Cobrar Facturas');
        $invoice->update([
            'rest'=>$amortizacion->suma,
        ]);
        $invoice->payment->update([
            'rest'=>$amortizacion->suma,
            'total'=>$amortizacion->suma+$invoice->payment->payed,
        ]);
    }
}
