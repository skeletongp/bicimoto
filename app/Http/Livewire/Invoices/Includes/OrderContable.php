<?php

namespace App\Http\Livewire\Invoices\Includes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait OrderContable
{
    public $gastoGeneral, $gastoTerminado;
    public function getGastos($invoice)
    {
        $details = $invoice->details;
        foreach ($details as $det) {
            if ($det->product->origin == 'Comprado') {
                $this->gastoGeneral += $det->cost * $det->cant;
            } else if ($det->product->origin == 'Procesado') {
                $this->gastoTerminado += $det->cost * $det->cant;
            }
        }
    }
    public function setTransaction($invoice, $payment, $client)
    {
        $place = $invoice->place;
        $this->getGastos($invoice);
        $creditable = $place->findCount('400-01');
        $ingresos_service = $place->findCount('400-02');
        $discount = $place->findCount('401-03');
        $ref = $invoice->comprobante ? $invoice->comprobante->ncf : $invoice->number;
        $moneys = array($payment->efectivo, $payment->tarjeta, $payment->transferencia, $payment->rest);
        $max = array_search(max($moneys), $moneys);
        $toTax = null;
        $anticipo = $place->findCount('206-01');
        $clientAnticipo=optional($client->anticipo);
        switch ($max) {
            case 0:
                setTransaction('Reg. venta de productos Ref. Nº. ' . $ref, $ref, ($moneys[$max] - $payment->tax), $place->cash(), $creditable, 'Cobrar Facturas');
                $toTax = $place->cash();

                break;
            case 1:
                setTransaction('Reg. venta de productos Ref. Nº. ' . $ref, $ref, ($moneys[$max] - $payment->tax), $place->check(), $creditable, 'Cobrar Facturas');

                $toTax =  $place->check();
                break;
            case 2:
                setTransaction('Reg. venta de productos Ref. Nº. ' . $ref, $this->reference, ($moneys[$max] - $payment->tax), $this->bank->contable()->first(), $creditable, 'Cobrar Facturas');
                $toTax =  $this->bank->contable()->first();
                break;
            case 3:
                setTransaction('Reg. venta de productos Ref. Nº. ' . $ref, $ref, (($moneys[$max] - $payment->tax)), $client->contable()->first(), $creditable, 'Cobrar Facturas');
                $toTax = $client->contable()->first();
                break;
        }
        $moneys[$max] = 0;

        $payment->update([
            'tax' => $invoice->payment->tax * (1 - $invoice->details->avg('discount_rate')),
        ]);
        setTransaction('Reg. venta de productos en Efectivo', $ref,  $moneys[0], $place->cash(), $creditable, 'Cobrar Facturas');
        if ($payment->efectivo > 0) {
            setTransaction('Tomado de anticipo', $ref, $clientAnticipo->saldo, $anticipo, $place->cash(), 'Cobrar Facturas');
        } else if ($payment->tarjeta > 0) {
            setTransaction('Tomado de anticipo', $ref, $clientAnticipo->saldo, $anticipo, $place->check(), 'Cobrar Facturas');
        } else if ($payment->transferencia > 0) {
            setTransaction('Tomado de anticipo', $ref, $clientAnticipo->saldo, $anticipo, $this->bank->contable, 'Cobrar Facturas');
        }
        if ($client->anticipo) {
            $client->anticipo->update([
                'saldo' => 0,
            ]);
        }

        setTransaction('Reg. vuelto de cambio', $ref,  $payment->cambio, $creditable, $place->cash(), 'Cobrar Facturas');
        setTransaction('Reg. venta de productos por Cheque', $ref,  $moneys[1], $place->check(), $creditable, 'Cobrar Facturas');


        setTransaction('Reg. venta de productos por Transferencia', $ref . ' | ' . $this->reference,  $moneys[2], optional($this->bank)->contable, $creditable, 'Cobrar Facturas');



        setTransaction('Reg. venta de productos a Crédito', $ref, ($moneys[3]),  $client->contable()->first(), $creditable, 'Cobrar Facturas');
        setTransaction('Descuento en productos a Fct. ' . $invoice->number, $ref, $payment->discount,  $discount, $creditable, 'Cobrar Facturas');

        $itbisCount = $place->findCount('203-01');
        setTransaction('Reg. retención de ITBIS', $ref, $payment->tax,   $toTax, $itbisCount, 'Cobrar Facturas');
        setTransaction('Reg. Costo Mercancía General Vendida', $ref, $this->gastoGeneral, $place->ventas(), $place->inventario(), 'Cobrar Facturas');
        setTransaction('Reg. Costo Producto Terminado Vendido', $ref, $this->gastoTerminado, $place->ventas(), $place->producto_terminado(), 'Cobrar Facturas');

        $client->update([
            'limit' => $client->limit - $invoice->payment->rest,
            'debt' => $client->invoices->sum('rest'),
        ]);
    }
    public function setTaxes($invoice)
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
                        'amount' => DB::raw('amount +' . $tax->rate * $detail->total)
                    ]
                );
            }
        }
    }
    public function closeComprobante($comprobante, $invoice)
    {
        if ($comprobante) {
            $comprobante->update([
                'status' => 'usado',
                'user_id' => $invoice->contable_id,
                'client_id' => $invoice->client_id,
            ]);
        }
    }
    public function setPendiente()
    {
        $dif = $this->form['total'] - $this->form['payed'];
        if ($dif > 0) {
            $this->form['rest'] = round($dif, 2);
            $this->form['cambio'] = 0;
        } else if ($dif < 0) {
            $this->form['cambio'] = 0 -  round($dif, 2);
            $this->form['rest'] = 0;
        } else {
            $this->form['rest'] = 0;
            $this->form['cambio'] = 0;
        }
    }
}
