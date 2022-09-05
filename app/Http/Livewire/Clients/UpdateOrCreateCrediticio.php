<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;

class UpdateOrCreateCrediticio extends Component
{
    public $client, $form, $banks;


    public function mount(){
        $this->form=$this->client->crediticio? $this->client->crediticio->toArray() : [];
        $this->form['id']=optional($this->client->crediticio)->id;
        $banks=file_get_contents(public_path('banks.json'));
        $banks=json_decode($banks, true);
        $banks=array_column($banks, '0');
        $this->banks=$banks;
    }

    public function rules()
    {
        return [
            "form.state" => 'required|min:0',
            "form.muebles" => 'required|min:0',
            "form.rent" => 'required|min:0',
            "form.hipoteca" => 'required|min:0',
            "form.loans" => 'required|min:0',
            "form.others" => 'required|min:0',
            "form.bank_value" => 'required|min:0',
            "form.bank" => 'required|string',
        ];
    }


    public function render()
    {
        return view('livewire.clients.update-or-create-crediticio');
    }

    public function createCrediticio()
    {
        $this->validate();
        $this->client->crediticio()->updateOrCreate(['id'=>$this->form['id']],$this->form);
        return redirect()->route('clients.show',$this->client);
    }



}
