<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Invoice;
use App\Models\Store;
use Carbon\Carbon;

trait InvoiceData
{

    public $number, $ncf, $condition = "DE CONTADO", $type, $vence, $seller, $compAvail = true, $comprobante_id;

    public function checkComprobante($type): bool
    {
        $store = optional(auth()->user())->store?:Store::first();
        $comprobante = $store->comprobantes()
            ->where('type', array_search($type, Invoice::TYPES))->where('status', 'disponible')
            ->orderBy('number')->first();
        if ($comprobante) {
            $this->comprobante_id = $comprobante->id;
            $this->type = $type;
            $this->ncf=$comprobante->ncf;
            return true;
        } else {
            $this->type = 'B00';
            $this->ncf='B0000000000';
            return false;
        };
        
    }
    public function updatedType()
    {
        $value = $this->type;

        if ($value != 'B00') {
            $this->compAvail = $this->checkComprobante($value);
            if (!$this->compAvail) {
                $this->form['type'] = 'B00';
            }
        }
    }
    public function updatedCondition()
    {
        $this->updatedCant();
    }
}
