<?php

namespace App\Http\Livewire\Banks;

use App\Models\Transaction;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class BankDebit extends LivewireDatatable
{
    public $bank;
    public $padding = "px-2";
    public $headTitle ;
    public $uniqueDate = 'true';
    protected $queryString = [
        'page' => ['except' => 1, 'as' => 'page_credit'],
        ];
    public function builder()
    {
        
        $transactions = $this->bank->transactions()->where('debitable_id', $this->bank->contable->id);
        $this->headTitle='Ingresos a la cuenta';
        return $transactions;
    }

    public function columns()
    {
        return [
            Column::checkbox(),
            DateColumn::name('created_at')->label('Fecha')->filterable()->width('20px'),
            Column::callback('income', function ($income) {
                return '$' . formatNumber($income);
            })->label('Monto'),
            Column::callback('concepto', function ($concept) {
                return ellipsis($concept, 25);
            })->label('Concepto')->searchable(),
            Column::name('ref')->label('Ref.'),
            Column::callback('status', function ($status) {
                return $status == 'Pendiente' ? '<span class="fas text-red-500 fa-clock"></span>' : '<span class="fas fa-check-circle text-green-500"></span>';
            })->label('Stat')->filterable(['Pendiente', 'Confirmado'])->contentAlignCenter()->width('20px'),
        ];
    }
    public function buildActions()
    {
        return [

            Action::value('confirm')->label('Confirmar')->callback(function ($mode, $items) {
                Transaction::whereIn('id', $items)->update([
                    'status' => 'Confirmado'
                ]);
                $this->emit('refresLivewireDatatable');
            }),
            Action::value('unconfirm')->label('Desconfirmar')->callback(function ($mode, $items) {
                Transaction::whereIn('id', $items)->update([
                    'status' => 'Pendiente'
                ]);
                $this->emit('refresLivewireDatatable');
            }),



        ];
    }
}
