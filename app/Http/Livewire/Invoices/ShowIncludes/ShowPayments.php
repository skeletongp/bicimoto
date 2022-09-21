<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use App\Jobs\CreatePDFJob;
use App\Models\Bank;
use App\Models\Cuota;
use App\Models\User;
use Carbon\Carbon;
use Livewire\WithFileUploads;

trait ShowPayments
{


    public $banks, $payment=['efectivo'=>0, 'tarjeta'=>0,'transferencia'=>0], $reference, $bank, $bank_id, $cobrable = true;

    public function rules2(): array
    {
        return [
            'payment.efectivo' => 'required|numeric|min:0',
            'payment.tarjeta' => 'required|numeric|min:0',

        ];
    }
    public function validateData()
    {
        $rules = $this->rules2();
        if (auth()->user()->store->banks->count()) {
            $rules = array_merge($rules, ['payment.transferencia' => 'required|numeric|min:0']);
        }
        if (!empty($this->payment['transferencia']) && $this->payment['transferencia'] > 0) {
            $rules = array_merge($rules, ['bank' => 'required']);
            $rules = array_merge($rules, ['reference' => 'required']);
        }

        $this->bank = Bank::find($this->bank_id);
        $this->validate($rules);
    }


    public function storePayment()
    {

        $this->validateData();
        if ($this->validateRate()) {
            $this->createPayment($this->invoice);
        }
    }
    public function validateRate()
    {
        $total = $this->payment['efectivo'] + $this->payment['tarjeta'] + $this->payment['transferencia'];
        if ($total < ($this->invoice->cuotas->sum('capital') * 0.2)) {
            $this->emit('showAlert', 'El monto a pagar es menor al 20% del total de la factura', 'warning');
            return false;
        }
        return true;
    }
    public function createPayment($invoice)
    {
        $subtotal = $this->invoice->payments()->orderBy('id', 'desc')->first()->rest;
        $discount = 0;
        $tax = 0;
        $total = $subtotal;
        $cambio = 0;
        $payed = array_sum($this->payment);
        $rest = 0;
        if ($total > $payed) {
            $rest = $total - $payed;
        } else {
            $cambio = $payed - $total;
        }
        $forma = 'cobro';

        if ($invoice->day == date('Y-m-d')) {
            $forma = $invoice->condition == 'De Contado' ? 'contado' : 'credito';
        }

        $data = [
            'ncf' => $invoice->payment->ncf,
            'amount' => $subtotal,
            'discount' => $discount,
            'total' =>  $total,
            'tax' =>  $tax,
            'payed' => array_sum($this->payment),
            'rest' =>  $rest,
            'forma' =>  $forma,
            'cambio' =>  $cambio,
            'efectivo' => $this->payment['efectivo'],
            'tarjeta' => $this->payment['tarjeta'],
            'contable_type' => User::class,
            'contable_id' => auth()->user()->id,
            'transferencia' => empty($this->payment['transferencia']) ? 0 : $this->payment['transferencia'],
        ];

        $invoice->payments()->save(setPayment($data));

        $payment = $invoice->payments()->orderBy('id', 'desc')->first();
        setIncome($invoice, 'Abono saldo Factura Nº. ' . $invoice->number, $payed);
        $invoice->client->payments()->save($payment);
        setPaymentTransaction($invoice, $payment, $invoice->client, $this->bank, $this->reference);
        $invoice->update([
            'rest' => $rest
        ]);
        if ($invoice->cuotas->count()) {
            $this->remakeAmortizacion($payment, $invoice);
        } else{
            $invoice->client->update([
                'debt' => $invoice->client->invoices->sum('rest'),
                'limit' => $invoice->client->limit + $payment->payed
            ]);
        }
        $this->emit('refreshLivewireDatatable');

        dispatch(new CreatePDFJob($invoice))->onConnection('sync');
        $this->emit('showAlert', 'Pago registrado exitosamente', 'success');
        $payment = $payment->load('payable.store', 'payer', 'payer', 'place.preference', 'payable.payment', 'contable');
        $this->emit('printPayment', $payment);
        $this->reset('payment', 'bank_id');
        $this->payment['efectivo'] = 0;
        $this->payment['tarjeta'] = 0;
        $this->payment['transferencia'] = 0;
    }
    public function remakeAmortizacion($payment, $invoice)
    {
        $payed = $payment->payed - $payment->cambio;
        $capital = $invoice->cuotas()->where('status', 'pendiente')
            ->sum('capital');
        $prevInteres = $invoice->cuotas()->where('status', 'pendiente')
            ->sum('interes');
        $invoice->update(
            [
                'rest' => $capital - $payed
            ]
        );
        $payment->update([
            'rest' => $capital - $payed,
            'payed' => $payed,
            'cambio' => 0,
            'forma' => 'cobro'
        ]);
        $date = $invoice->cuotas()->where('status', 'pendiente')->orderBy('fecha', 'asc')->first()->fecha;
        $cuotas = $invoice->cuotas()->where('status', 'pendiente')->count('cuotas.id');
        $interes = $invoice->contrato->interes;
        $periodo = $invoice->cuotas()->first()->periodo;
        $invoice->cuotas()->where('status', 'pendiente')->delete();
        $this->createNewCuota($invoice, $date, $interes, $cuotas,  $prevInteres, $payment, $periodo);
    }
    public function createNewCuota($invoice, $date, $interes, $cuotas, $prevInteres, $payment, $periodo)
    {
        $fecha = Carbon::createFromDate($date);
        $amortizacion = amortizar($invoice->rest, $interes, $cuotas);

        foreach ($amortizacion->pagos as $pagos) {
            $new_fecha = sumarfecha($fecha, $periodo);
            $cuota = new Cuota();
            $cuota->fecha = $new_fecha;
            $cuota->periodo = $periodo;
            $cuota->invoice_id = $invoice->id;
            $cuota->client_id = $invoice->client_id;
            $cuota->saldo = $pagos->saldo;
            $cuota->interes = $pagos->interes;
            $cuota->capital = $pagos->capital;
            $cuota->debe = $pagos->debe;
            $cuota->restante = $pagos->restante;
            $cuota->save();
            $fecha = $new_fecha;
        }
        $place = $invoice->place;
        $client = $invoice->client;
        setTransaction('Descuento por pronto pago', $invoice->number, $prevInteres - (array_sum(array_column($amortizacion->pagos, 'interes'))), $place->findCount('401-03'), $client->contable()->first(), 'Cobrar Facturas');
        $invoice->update([
            'rest' => $amortizacion->suma,
        ]);
        $payment->update([
            'rest' => $amortizacion->suma,
            'total' => $amortizacion->suma + $invoice->payment->payed,
        ]);
        $invoice->client->update([
            'debt' => $invoice->client->invoices->sum('rest'),
            'limit' => $invoice->client->limit + $payment->payed
        ]);
    }
}
