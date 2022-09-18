<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait ClientSectionTrait
{

    public $client, $client_code, $clientNameCode, $clients=[], $name, $rnc;

    public function changeClient()
    {
        $code = str_pad($this->client_code, 4, '0', STR_PAD_LEFT);
        $client = Client::where('code', $code)->with('contact')->first();
        if ($client) {
            $contact=$client->contact;
            $this->client = [
                'fullname' => $contact->fullname,
                'address' => $contact->address,
                'phone' => $contact->phone,
                'special' => $contact->special,
                'email' => $client->email,
                'rnc' => $contact->cedula,
                'id' => $client->id,
                'balance' => '$' . formatNumber($client->debt),
                'limit' => $client->limit,
                'name' => $contact->name,
                'code' => $client->code,
            ];
            $this->emit('focusCode');
            if($client->id!==1){
                $this->name=$client->contact->fullname;
            }
            $this->client_code = $code;
            $this->clientNameCode = $code.' - '.$contact->fullname;
        }
    }
    public function updatingClientNameCode($value){
        $data=explode('-',$value);
        if(count($data) ==2){
            $this->client_code=trim($data[0]);
            $this->clientNameCode=$data[1];
            $this->updatedClientCode();
        }
    }
    public function realoadClients()
    {
        Cache::forget('clientsWithCode_'.env('STORE_ID'));
    }

    public function updatedClientCode()
    {
        $this->changeClient();
    }
    /* public function rncEnter()
    {
        $url='contribuyentes/'.$this->name;
        $client=Client::whereRaw("REPLACE(cedula,'-','')=?", [$this->name])
        ->orWhere('name',$this->name)->first();
        if ($client) {
            $this->client_code=$client->code;
            $this->changeClient();
            $this->name=null;
            return;
        }
        $client=getApi($url);
        if (array_key_exists('model', $client)) {
            $this->loadFromRNC($client['model']);
        }
    } */
    public function loadFromRNC($client)
    {
       $this->rnc=$client['id'];
       $this->name=$client['name'];
       $this->render();
    }
}
