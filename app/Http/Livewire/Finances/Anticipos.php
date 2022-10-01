<?php

namespace App\Http\Livewire\Finances;

use App\Http\Classes\NumberColumn;
use App\Models\Anticipo;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Anticipos extends LivewireDatatable
{
    public $padding="px-2";
    public $headTitle="Anticipos Cobrados";
    public function builder()
    {
        $anticipos=Anticipo::
        leftJoin('clients','clients.id','=','anticipos.client_id')
        ->leftJoin('contacts','contacts.id','=','clients.contact_id')
        ;
        return $anticipos;
    }

    public function columns()
    {
        return [
            Column::callback(['clients.id','clients.code'], function($id, $code){
                return "<a class='text-blue-700 hover:text-blue-400 hover:underline' href='".route('clients.show',$id)."'>".$code."</a>";
            })->label('Cód.'),
            Column::name('contacts.fullname')->label('Cliente')->searchable(),
            NumberColumn::name('anticipos.saldo')->label('Saldo')->formatear('money')->enableSummary(),
            DateColumn::name('anticipos.updated_at')->label('Fecha')->format('d/m/Y'),
            Column::callback(['anticipos.id'], function($id){
                return view('livewire.anticipos.action', ['anticipo_id' => $id]);
            })->label('Opc.'),
        ];
    }

    public function summarize($column)
    {
        
        $results = json_decode(json_encode($this->results->items()), true);
        foreach ($results as $key => $value) {
            $val = json_decode(json_encode($value), true);
            $results[$key][$column] = preg_replace("/[^0-9 .]/", '', $val[$column]);
        }
        try {

            return "<h1 class='font-bold text-right'>" . '$' . formatNumber(array_sum(array_column($results, $column))) . "</h1>";;
        } catch (\TypeError $e) {
            return '';
        }
    }
}