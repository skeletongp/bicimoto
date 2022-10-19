<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use App\Models\Payment;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PaymentsFromInvoice extends LivewireDatatable
{
    public $invoice;
    public $hideable="select";
    public $headTitle='Historial de pagos';
    public $padding='px-2';
    public function builder()
    {
        return $this->invoice->payments()->with('payable.store', 'payable.payment', 'payer', 'payer', 'place.preference', 'contable')->orderBy('id','desc');
    }

    public function columns()
    {
        
        return [
            DateColumn::name('created_at')->label('Fecha')->hide()->format('d/m/Y H:i'),
            Column::name('total')->callback(['total'], function ($total) {
                return '$' . formatNumber($total);
            })->label('Monto'),
            Column::name('efectivo')->callback(['efectivo'], function ($efectivo) {
                return '$' . formatNumber($efectivo);
            })->label('Efectivo'),
            Column::name('transferencia')->callback(['transferencia'], function ($transferencia) {
                return '$' . formatNumber($transferencia);
            })->label('Transf.'),
            Column::name('tarjeta')->callback(['tarjeta'], function ($tarjeta) {
                return '$' . formatNumber($tarjeta);
            })->label('Otros'),

            Column::name('payed')->callback(['payed'], function ($payed) {
                return "<b>$". formatNumber($payed)."</b>";
            })->label('Pagado'),
            Column::callback(['rest'], function ($rest) {
                return  "<span class='text-red-400 font-bold'>$". formatNumber($rest)."</span>";
            })->label('Resta'),
            Column::callback(['cambio'], function ($cambio) {
                return  "<span class='text-blue-600 font-bold'>$". formatNumber($cambio)."</span>";
            })->label('Cambio'),
            Column::callback(['payer_id', 'id'], function ($payer, $id)  {
                return  "<span class='far fa-print cursor-pointer' wire:click='print($id)'> </span>";
            })->label('Print')->contentAlignCenter(),
            Column::delete()->label('Del.')
        ];
    }
    public function print($id)
    {
        $payments=$this->builder()->get()->toArray();
        $result=arrayFind($payments, 'id', $id);
        $this->emit('printPayment', $result);
    }
    public function delete($id)
    {
        $user=auth()->user();
        if($user->hasRole('Super Admin')){
            $place=getPlace();
            $payment=Payment::with('payable.client','payer')->whereId($id)->first();
          
            $this->backToInvoice($payment->payable, $payment->payed-$payment->cambio);
            setTransaction('Reversión pago de '.$payment->payer->name, $payment->payable->number, $payment->payed-$payment->cambio, $payment->payer->contable, $place->cash());
            $payment->delete();
            $this->emit('alert', 'El pago ha sido eliminado');
        }else{
            $this->emit('alert', 'No puede eliminar pagos','error');
        }
        $payment->delete();
        $this->emit('alert', ['type' => 'success', 'message' => 'Pago eliminado']);
    }
    public function backToInvoice($invoice, $payed){
        $invoice->update([
            'rest' => $invoice->rest + $payed,
        ]);
        $invoice->client->update([
            'debt'=>$invoice->client->invoices->sum('rest'),
            'limit'=>$invoice->client->limit+$payed
        ]);
    }
    
    
}
