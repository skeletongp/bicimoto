<?php

namespace App\Http\Livewire\Anticipos;

use App\Models\Bank;
use App\Models\Client;
use App\Models\Count;
use Livewire\Component;
use Termwind\Components\Dd;

class CreateAnticipo extends Component
{
    protected $listeners = ['modalOpened'];
    public $users = [];
    public $anticipo;
    public $banks = [];
    public $bank_id, $bank, $reference;
    public $clients = [];
    public $payway = 'Efectivo';
    public $clientId;
    public $client;
    public function render()
    {

        return view('livewire.anticipos.create-anticipo');
    }
    public function modalOpened()
    {
        $store = auth()->user()->store;
        $this->anticipo['efectivo'] = 0;
        $this->anticipo['transferencia'] = 0;
        $this->anticipo['tarjeta'] = 0;
        $this->banks = $store->banks()->pluck('bank_name', 'id');
        $this->clients = clientWithCode($store->id);
    }
    public function updatedClientId()
    {

        $this->client = Client::where('id', $this->clientId)->first();
    }
    public function updatedPayway()
    {
        $this->anticipo['efectivo'] = 0;
        $this->anticipo['transferencia'] = 0;
        $this->anticipo['tarjeta'] = 0;
    }

    protected $rules = [
        'payway' => 'required',
        'client' => 'required',
        'anticipo.efectivo' => 'required|numeric',
        'anticipo.tarjeta' =>  'required|numeric',
        'anticipo.transferencia' => 'required|numeric',
    ];
    public function createAnticipo()
    {
        if (array_key_exists('transferencia', $this->anticipo) && $this->anticipo['transferencia'] > 0) {
            $this->rules = array_merge($this->rules, ['bank' => 'required']);
            $this->rules = array_merge($this->rules, ['reference' => 'required']);
        }
        $this->bank = Bank::find($this->bank_id);
        $this->validate();
        $total= $this->anticipo['efectivo'] + $this->anticipo['tarjeta'] + $this->anticipo['transferencia'];
        if(!$total>0){
            $this->emit('showAlert','El anticipo debe ser mayor a 0','error');
            return;
        }
        $this->newAnticipo($total);
    }
    public function newAnticipo($total)
    {
        $client = $this->client;
        if ($client->anticipo) {
            $client->anticipo->update([
                'saldo' => $client->anticipo->saldo + $total,
            ]);
        } else {
            $client->anticipo()->create([
                'saldo' => $total,
                'place_id' => auth()->user()->place->id,
            ]);
        }
        $this->setTransaction();
        $this->emit('showAlert','Anticipo creado correctamente','success');
    }
    public function setTransaction(){
        $place=auth()->user()->place;
        $anticipo=$place->findCount('206-01');
        setTransaction('Abono de '.$this->client->contact->fullname, $this->client->code, $this->anticipo['efectivo'], $place->cash(), $anticipo, 'Cobrar Facturas');
        setTransaction('Abono de '.$this->client->contact->fullname, $this->client->code, $this->anticipo['tarjeta'], $place->other(), $anticipo, 'Cobrar Facturas');
        setTransaction('Abono de '.$this->client->contact->fullname, $this->client->code.' | '.$this->reference, $this->anticipo['transferencia'], optional($this->bank)->contable, $anticipo, 'Cobrar Facturas');
    }
}
