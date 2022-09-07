<?php

namespace App\Http\Livewire\Clients;

use App\Jobs\CreatePDFJob;
use App\Models\Bank;
use App\Models\Cuota;
use App\Models\Invoice;
use App\Models\Place;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PayCuotas extends Component
{
    public $cuotas, $banks, $confirmed = false, $total, $cuotasIds;
    public $amount, $payway = 'Efectivo', $ref, $bank_id;

    public function mount($cuotas)
    {
        $store = auth()->user()->store;
        $this->banks = $store->banks()->select(DB::raw('CONCAT(bank_name," ",bank_number) AS name, id'))->pluck('name', 'banks.id');
        $this->cuotasIds = $cuotas;
        $cuotas = explode(',', $cuotas);
        $cuotas = Cuota::whereIn('cuotas.id', $cuotas)
            ->where('cuotas.status', 'pendiente')
            ->leftjoin('invoices', 'cuotas.invoice_id', '=', 'invoices.id')
            ->select('cuotas.*', 'invoices.number')
            ->orderBy('created_at')->get();
        foreach ($cuotas as $cuota) {
            $this->adjustMora($cuota);
        }
        $this->cuotas = $cuotas;
        $this->total = $cuotas->sum('debe');
    }
    public function adjustMora($cuota)
    {
        $place=optional(auth()->user())->place?:Place::first();
        $mora = $place->preference->mora_rate/100;
        if (
            $cuota->fecha < Carbon::now()->subDays(5)->format('Y-m-d')
            && $cuota->updated_at->format('Y-m-d H:i') ==  $cuota->created_at->format('Y-m-d H:i')
        ) {
            
            $cuota->mora= $cuota->debe *$mora;
            $cuota->debe = $cuota->debe * (1+$mora);
            $cuota->save();
            $invoice=$cuota->invoice->load('payments');
            $invoice->update([
                'rest'=>$invoice->rest+$cuota->mora,
            ]);
            $payment=$invoice->payments->last();
            $payment->update([
                'total'=>$payment->total+$cuota->mora,
                'rest'=>$payment->rest+$cuota->mora,
            ]);
            $cuota->touch();
        }
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:' . $this->total,
            'payway' => 'required',

        ];
    }

    public function render()
    {
        return view('livewire.clients.pay-cuotas');
    }
    public function payCuota($cuota_id)
    {
        if ($this->payway == 'Transferencia') {
            $this->rules = array_merge($this->rules(), ['bank_id' => 'required']);
        }
        $this->validate();
        $cuota = Cuota::find($cuota_id)->load('invoice');
        $invoice = $cuota->invoice;
        $this->amount -= $cuota->debe;
        $this->createPayment($invoice, $cuota->capital, $cuota->interes, $cuota->debe, $cuota);
    }
    public function createPayment($invoice, $capital, $interes, $debe, $cuota)
    {
        $efectivo = 0;
        $transferencia = 0;
        $tarjeta = 0;
        $cambio = 0;

        if (count($this->cuotas) == 1) {
            $cambio = $this->amount;
        }
        switch ($this->payway) {
            case 'Efectivo':
                $efectivo = $debe + $cambio;
                break;
            case 'Transferencia':
                $transferencia = $debe + $cambio;
                break;
            case 'Otra':
                $tarjeta = $debe + $cambio;
                break;
        }

        $data = [
            'ncf' => $invoice->payment->ncf,
            'amount' => $invoice->rest,
            'discount' => 0,
            'total' => $invoice->rest,
            'tax' =>  0,
            'payed' => $debe,
            'rest' =>  $invoice->rest - $debe,
            'forma' =>  'Cobro',
            'cambio' =>  $cambio,
            'efectivo' => $efectivo,
            'tarjeta' => $tarjeta,
            'contable_type' => User::class,
            'contable_id' => auth()->user()->id,
            'transferencia' => $transferencia,
        ];
        $payment = setPayment($data);
       
        $invoice->payments()->save($payment);
        $bank = Bank::find($this->bank_id);
        $invoice->client->payments()->save($payment);
        $this->setTransactions($efectivo, $tarjeta, $transferencia, $cambio, $capital+$cambio, $interes, $bank, $invoice->number, $invoice->client, $cuota);
        $invoice->update([
            'rest' => $data['rest']
        ]);
        $invoice->client->update([
            'debt'=>$invoice->client->invoices->sum('rest'),
            'limit'=>$invoice->client->limit+$payment->payed
        ]);
        $this->emit('refreshLivewireDatatable');
        dispatch(new CreatePDFJob($invoice))->onConnection('sync');
        $this->emit('showAlert', 'Pago registrado exitosamente', 'success');
        $payment = $payment->load('payable.store', 'payer', 'payer', 'place.preference', 'payable.payment', 'contable');
        $cuota->update([
            'status' => 'pagado',
            'payed_at' => now(),
            'payment_id' => $payment->id,

        ]);
        $cuotas=$invoice->cuotas->whereNotNull('payed_at')->count();
        $pendientes=$invoice->cuotas->whereNUll('payed_at')->count();
        $cuotasTotal=$invoice->cuotas->count();
        $payment=$payment->load('payable.store', 'payer', 'payer.contact', 'place.preference', 'payable.payment', 'contable');
        $proxima=$invoice->cuotas->whereNUll('payed_at')->first();
        $payment->cuota=$cuota;
        $payment->cuotas=$cuotas;
        $proxima->fecha=Carbon::parse($proxima->fecha)->format('d/m/Y');
        $payment->day=Carbon::parse($payment->day)->format('d/m/Y');
        $payment->pendientes=$pendientes;
        $payment->cuotasTotal=$cuotasTotal;
        $payment->proxima=$proxima;
        $this->emit('printPayment', $payment);
        $this->mount($this->cuotasIds);
    }
    public function setTransactions($efectivo, $tarjeta, $transferencia, $cambio, $capital,  $interes, $bank, $ref, $client, $cuota)
    {
        $place = auth()->user()->place;
        $creditable =  $client->contable()->first();

        /* Registrar el pago del capital */
        setTransaction('Pago de cuota del ' . Carbon::parse($cuota->fecha)->format('d/m/Y'), $ref, $efectivo > 0 ? $efectivo - $interes : 0, $place->cash(),  $creditable, 'Cobrar Facturas');
        setTransaction('Pago de cuota del ' . Carbon::parse($cuota->fecha)->format('d/m/Y'), $ref, $tarjeta > 0 ? $tarjeta - $interes : 0, $place->check(),  $creditable, 'Cobrar Facturas');
        setTransaction('Pago de cuota del ' . Carbon::parse($cuota->fecha)->format('d/m/Y'), $ref, $transferencia > 0 ? $transferencia - $interes : 0, optional($bank)->contable,  $creditable, 'Cobrar Facturas');
        setTransaction('Vuelto de Cambio ', $ref, $cambio, $creditable,  $place->cash(), 'Cobrar Facturas');


        /* Registrar el pago del interés */
        setTransaction('Interés de cuota del ' . Carbon::parse($cuota->fecha)->format('d/m/Y'), $ref, $efectivo > 0 ? $efectivo - $capital : 0, $place->cash(),  $creditable, 'Cobrar Facturas');
        setTransaction('Interés de cuota del ' . Carbon::parse($cuota->fecha)->format('d/m/Y'), $ref, $tarjeta > 0 ? $tarjeta - $capital : 0, $place->check(),  $creditable, 'Cobrar Facturas');
        setTransaction('Interés de cuota del ' . Carbon::parse($cuota->fecha)->format('d/m/Y'), $ref, $transferencia > 0 ? $transferencia - $capital : 0, optional($bank)->contable,  $creditable, 'Cobrar Facturas');
        $anticipo=$place->findCount('206-01');
        if ($efectivo>0) {
            setTransaction('Tomado de anticipo',$ref, $client->anticipo->saldo, $anticipo, $place->cash(), 'Cobrar Facturas');
        } else if($tarjeta>0) {
            setTransaction('Tomado de anticipo',$ref, $client->anticipo->saldo, $anticipo, $place->check(), 'Cobrar Facturas');
        } else if($transferencia>0) {
            setTransaction('Tomado de anticipo',$ref, $client->anticipo->saldo, $anticipo, $this->bank, 'Cobrar Facturas');
        } 
        $client->anticipo->update([
            'saldo'=>0,
        ]);
    }
}
