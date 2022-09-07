<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Classes\NumberColumn;
use App\Models\Cuota;
use App\Models\Place;
use Illuminate\Support\Facades\Cache;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CuotasVencidas extends LivewireDatatable
{
    public $padding = "px-2";
    public $headTitle = "Cuotas en estado de mora";
    public $hideable="select";
    public function builder()
    {
        $user = auth()->user();
        $cuotas =
            Cuota::where('cuotas.fecha', '<', date('Y-m-d'))
            ->where('cuotas.status', '!=', 'pagado')
            ->orderBy('cuotas.fecha')
            ->leftjoin('clients', 'clients.id', '=', 'cuotas.client_id')
            ->leftjoin('invoices', 'invoices.id', '=', 'cuotas.invoice_id')
            ->leftJoin('contacts', 'contacts.id', '=', 'clients.contact_id')
            ->where('invoices.place_id', $user->place_id);
            Cache::put('cuotasvencidas'.env('STORE_ID'), $cuotas->count());
        return $cuotas;
    }

    public function columns()
    {
        $place=optional(auth()->user())->place?:Place::first();
        $moraRate = $place->preference->mora/100;
        return [
            Column::checkbox(),
            DateColumn::name('fecha')->label('Fecha')->format('d-m-Y'),
            NumberColumn::name('saldo')->label('Saldo')->formatear('money'),
            NumberColumn::name('interes')->label('Interés')->formatear('money'),
            NumberColumn::name('capital')->label('Capital')->formatear('money'),
            Column::callback(['mora', 'debe'], function ($mora, $debe) use ($moraRate) {
                if (!$mora) {
                    return '$'.formatNumber($debe * $moraRate);
                }
                return '$'.formatNumber($mora);
            })->label('Mora')->contentAlignRight(),
            Column::callback(['debe', 'mora'], function ($debe, $mora) use ($moraRate) {
                if (!$mora) {
                    return '$'.formatNumber($debe * (1+$moraRate));
                }
                return '$'.formatNumber($debe);
            })->label('Cuota')->contentAlignRight(),
           
            NumberColumn::name('restante')->label('Bal. Final')->formatear('money')->hide(),
            Column::callback('contacts.fullname', function ($client) {
                return ellipsis($client, 30);
            })->label('Cliente')->searchable(),
            Column::name('contacts.cellphone')->label('Celular')->hide(),

        ];
    }
    public function buildActions()
    {
        return [

            Action::value('edit')->label('Cobrar cuotas')->callback(function ($mode, $items) {
                return redirect()->route('clients.paymany', ['cuotas' => implode(',', $items)]);
            }),

        ];
    }
}