<?php

namespace App\Http\Livewire\Anticipos;

use App\Http\Classes\NumberColumn;
use App\Http\Livewire\UniqueDateTrait;
use App\Models\Transaction;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class AnticipoList extends LivewireDatatable
{
    use UniqueDateTrait;

    public $client_code;
    public $headTitle="Anticipos Registrados";
    public function builder()
    {
        $place=getPlace();
        $anticipo=$place->findCount('206-01');
        $anticipos=Transaction::where('creditable_id',$anticipo->id)
        ->where('ref', 'like', '%'.$this->client_code)
        ->leftJoin('counts','counts.id','=','transactions.debitable_id')
        ;
        return $anticipos;
    }

    public function columns()
    {
        return [
            DateColumn::name('created_at')->label('Fecha')->filterable()->format('d/m/Y H:i A'),
            NumberColumn::name('income')->label('Ingreso')->formatear('money'),
            Column::name('counts.name')->label('Destino')
        ];
    }
}