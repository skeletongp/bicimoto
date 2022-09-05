<?php

namespace App\Http\Livewire\Clients;

use App\Http\Classes\NumberColumn;
use App\Models\Cuota;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ClientCuota extends LivewireDatatable
{
    public $client_id;
    public $padding = "px-2";
    public $headTitle = "Cuotas pendientes del cliente";
    public function builder()
    {
        $cuotas=Cuota::where('cuotas.client_id',$this->client_id)
        ->join('invoices','cuotas.invoice_id','=','invoices.id')
        ->where('cuotas.status','pendiente')
        ->orderBy('cuotas.fecha')
        ;

        return $cuotas;
    }

    public function columns()
    {
        return [
            DateColumn::name('fecha')->label('Fecha')->format('d-m-Y'),
            NumberColumn::name('saldo')->label('Saldo')->formatear('money'),
            NumberColumn::name('interes')->label('Interés')->formatear('money'),
            NumberColumn::name('capital')->label('Capital')->formatear('money'),
            NumberColumn::name('debe')->label('Cuota')->formatear('money'),
        ];
    }
}