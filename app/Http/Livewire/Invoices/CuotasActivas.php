<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Classes\NumberColumn;
use App\Models\Cuota;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CuotasActivas extends LivewireDatatable
{
    public $padding = "px-2";
    public $headTitle = "Cuotas activas de los siguientes 5 días";
    public function builder()
    {
        $user = auth()->user();
        $cuotas =
            Cuota::whereBetween('cuotas.fecha', [date('Y-m-d', strtotime('-4 days')), date('Y-m-d', strtotime('+5 days'))])
            ->where('cuotas.status', '!=', 'pagado')
            ->orderBy('cuotas.fecha')
            ->leftjoin('clients', 'clients.id', '=', 'cuotas.client_id')
            ->leftjoin('invoices', 'invoices.id', '=', 'cuotas.invoice_id')
            ->leftJoin('contacts', 'contacts.id', '=', 'clients.contact_id')
            ->where('invoices.place_id', $user->place_id);
        Cache::put('cuotasactivas' . env('STORE_ID'), $cuotas->count());
        return $cuotas;
    }

    public function columns()
    {
        return [
            Column::checkbox(),
            DateColumn::name('fecha')->label('Fecha')->format('d-m-Y'),
            Column::callback(['invoices.id', 'invoices.number'], function ($id, $number) {
                $number=ltrim(substr($number, strpos($number, '-') + 1), '0');
                return "<a href=" . route('invoices.show', $id) . " class='hover:underline hover:text-blue-500'>" . str_pad($number, 3,"0",STR_PAD_LEFT) . "</a>";;
            })->label('Fact'),
            NumberColumn::name('saldo')->label('Saldo')->formatear('money'),
            NumberColumn::name('interes')->label('Interés')->formatear('money'),
            NumberColumn::name('capital')->label('Capital')->formatear('money'),
            NumberColumn::name('debe')->label('Cuota')->formatear('money'),
            NumberColumn::name('restante')->label('Bal. Final')->formatear('money'),
            Column::callback('contacts.fullname', function ($client) {
                return ellipsis($client, 30);
            })->label('Cliente')->searchable(),

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
