<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use App\Models\Contact;
use App\Models\CountMain;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateClient extends Component
{
    public $form, $avatar, $photo_path, $store_id, $role,  $name, $lastname, $cellphone, $code;
    use WithFileUploads;
    protected $listeners=['modalOpened'];
    public function modalOpened(){
        $store = auth()->user()->store;
        $num = $store->clients()->count() + 1;
        $code = str_pad($num, 3, '0', STR_PAD_LEFT);
        $this->code = $code;
        $this->form['code'] = $code;
    }
    public function render()
    {

       
        return view('livewire.clients.create-client');
    }
    protected $rules = [
        'form.name' => 'required|max:50',
        'form.lastname' => 'required|max:50',
        'form.email' => 'required|email|max:100|unique:contacts,email',
        'form.address' => 'required|string|max:100',
        'form.phone' => 'required|string|max:25',
        'form.nacionality' => 'required|string|max:50',
        'form.genre' => 'required|string|max:25',
        'form.civil_status' => 'required|string|max:25',
        'form.cedula' => 'required|string|max:25',
        'form.cellphone' => 'required|string|max:25',
    ];
    public function createClient()
    {
      
        $store = auth()->user()->store;
        $this->validate();
        $client = $store->clients()->create();
        $contact=Contact::create(Arr::except($this->form, ['code']));
          $client->contact()->associate($contact);
        $client->save();
        if ($this->photo_path) {
            $client->image()->create([
                'path' => $this->photo_path
            ]);
        }
        setContable($client, '101', 'debit', $contact->name.' '.$contact->lastname, null, true);
        $this->emit('realoadClients');
        Cache::forget('clientsWithCode_' . $store->id);
        Cache::forget('clientsWithId_' . $store->id);
        $this->reset();
        $this->render();
        $this->emit('showAlert', 'Cliente registrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
        $this->modalOpened();
    }
    public function updatedAvatar()
    {
        $ext = pathinfo($this->avatar->getFileName(), PATHINFO_EXTENSION);
        $photo = $this->avatar->storeAs('clients', date('Y_m_d_H_i_s') . '.' . $ext);
        $this->photo_path = asset("storage/{$photo}");
    }
    function loadFromRNC()
    {
        if (array_key_exists('cedula', $this->form)) {
            $rnc=str_replace('-', '', $this->form['cedula']);
            $client = getApi('contribuyentes/' . $rnc);
            if ($client && array_key_exists('model', $client)) {
                $client = $client['model'];
                if (strlen($rnc) == 9){
                    $this->form['name'] = $client['name'];
                } else {
                    $this->name = strtok($client['name'], ' ');
                    $this->lastname=substr($client['name'],strlen($this->name));
                }
                
            }
        }
    }
}
