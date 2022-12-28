<?php

namespace App\Http\Livewire\Clients;

use App\Models\Contact;
use Livewire\Component;

class UpdateOrCreateRelacionado extends Component
{
    public $client;
    public $form,   $role, $cltDocType, $name, $lastname, $cellphone, $contact_id, $relacionado_id;

    public function mount()
    {
        $this->contact_id = optional($this->client->relacionado)->contact_id;
        $this->relacionado_id = optional($this->client->relacionado)->id;
        $this->form = optional($this->client->relacionado)->contact? $this->client->relacionado->contact->toArray() : [];
        if (optional($this->client->relacionado)->contact) {
            if (strlen($this->client->relacionado->contact->cedula) == 13) {
                $this->cltDocType = 'Cédula';
            } else {
                $this->cltDocType = 'RNC';
            }
        }
    }

    public function render()
    {
        return view('livewire.clients.update-or-create-relacionado');
    }
    function rules()
    {
        return [
            'form.name' => 'required|max:50',
            'form.lastname' => 'required|max:50',
            'form.email' => 'required|email|max:100|unique:contacts,email,'.$this->contact_id,
            'form.address' => 'required|string|max:100',
            'form.phone' => 'string|max:25',
            'form.nacionality' => 'required|string|max:50',
            'form.genre' => 'required|string|max:25',
            'form.civil_status' => 'required|string|max:25',
            'form.cedula' => 'required|string|max:25',
            'form.cellphone' => 'required|string|max:25',
            'cltDocType' => 'required',
        ];
    }
    public function createRelacionado()
    {

        $store = auth()->user()->store;
        $this->validate();
        $client = $this->client;
        $relacionado = $client->relacionado()->updateOrCreate([
            'id' => $this->relacionado_id,
        ], []);
        $contact = Contact::updateOrCreate(['id' => $this->contact_id], $this->form);
        $relacionado->contact()->associate($contact);
        $relacionado->save();
        return redirect(request()->header('Referer'));
    }
}
