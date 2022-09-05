<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;

class CreateCuota extends Component
{
    public $invoice_id;

    protected $listeners = ['modalOpened'];
    public function render()
    {
        return view('livewire.invoices.create-cuota');
    }
    public function modalOpened()
    {
        $invoice=Invoice::find($this->invoice_id);
    }
}
