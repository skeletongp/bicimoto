<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Helper\Universal;
use App\Http\Livewire\Invoices\Includes\OrderConfirmTrait;
use App\Http\Livewire\Invoices\Includes\OrderContable;
use App\Http\Traits\Livewire\Confirm;
use App\Models\Bank;
use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderConfirm extends Component
{
    use OrderContable, OrderConfirmTrait, WithFileUploads, Confirm;
    public  $form = [], $compAvail = true, $cobrable = true, $copyCant = 1;
    public $banks, $bank, $bank_id, $reference;
    public $payway = 'Efectivo';
    protected $listeners = ['payInvoice', 'validateAuthorization', 'reload' => 'render', 'modalOpened'];
    public $invoice_id;
    public $createCuota = 0;
    public $cuotas, $periodo='mensual', $interes=3;
    public $tipo="ND", $marca="ND", $modelo="ND", $color="ND", $chasis="ND", $year="ND", $placa='EN TRÁMITE';
    public $instant=false;
    
    public function mount($invoice_id)
    {
        $this->form['id'] = $invoice_id;
        $this->form['rest'] = 0;
    }

    public function updatedCopyCant()
    {
        $this->emit('changeCant', $this->copyCant);
    }
    public function render()
    {
        return view('livewire.invoices.order-confirm');
    }
    public function updatedCreatecuota()
    {
        $this->form['efectivo'] = 0;
        $this->form['transferencia'] = 0;
        $this->form['tarjeta'] = 0;
    }
    public function updatedPayway()
    {
        $this->form['efectivo'] = 0;
        $this->form['transferencia'] = 0;
        $this->form['tarjeta'] = 0;
    }
    public function updatedForm($value, $key)
    {
        switch ($key) {
            case 'efectivo':
            case 'tarjeta':
            case 'transferencia':
                $this->form['payed'] = floatval($value);
                break;
            default:
                # code...
                break;
        }
        $this->form['total'] = round(floatval($this->form['amount']) + floatval($this->form['tax']) - floatval($this->form['discount']), 2);
        $this->fiado = $this->form['rest'];
        $this->setPendiente();
    }

    public function validateData($invoice)
    {
        $rules = orderConfirmRules();

        if (array_key_exists('transferencia', $this->form) && $this->form['transferencia'] > 0) {
            $rules = array_merge($rules, ['bank' => 'required']);
            $rules = array_merge($rules, ['reference' => 'required']);
        }
        if ($this->createCuota) {
            $rules = array_merge($rules, ['cuotas' => 'required']);
            $rules = array_merge($rules, ['periodo' => 'required']);
            $rules = array_merge($rules, ['interes' => 'required']);
        }

        $this->bank = Bank::find($this->bank_id);
        $this->validate($rules);
    }
    public function modalOpened()
    {
        $this->form = Invoice::find($this->invoice_id)
            ->load('seller',  'client.contact', 'details.product.units', 'details.taxes', 'details.unit', 'payment.pdf', 'store.image', 'comprobante', 'pdf', 'place.preference')->toArray();
        $payment = $this->form['payment'];
        unset($payment['id']);
        $this->form['name'] = $this->form['name'] ?: $this->form['client']['contact']['fullname'];
        unset($this->form['payment']);
        $this->form = array_merge($this->form, $payment);
        /*   if ($invoice['client']['debt']>0 || $invoice['condition']!='De Contado') {
           $this->cobrable=false;
        } */
        $this->form['contable_id'] = optional(auth()->user())->id?:1;
        $this->form['condition']=='De Contado' ? $this->createCuota=0 : $this->createCuota=1;
        $this->updatedForm($this->form['efectivo'], 'efectivo');
    }
}
