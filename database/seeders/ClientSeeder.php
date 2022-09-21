<?php

namespace Database\Seeders;

use App\Http\Livewire\Invoices\CreateInvoice;
use App\Http\Livewire\Invoices\OrderConfirm;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Place;
use App\Models\ProductPlaceUnit;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = file_get_contents(public_path("../database/seeders/clients.json"));
        $newClt = json_decode($data, true);
        foreach ($newClt as $clt) {
            if(empty($clt['email'])) {
                $clt['email'] = mb_strtolower(str_replace(' ','',$clt['name'])).$clt['id'] . '@gmail.com';
            };
            $this->createClient($clt);
        }
        Cache::forget('clientsWithCode_' .env('STORE_ID'));
        Cache::forget('clientsWithId_' . env('STORE_ID'));
    }
    public function createClient($data)
    {
        $store = Store::find(env('STORE_ID'));
        $client = $store->clients()->create([
            'limit' => $data['deuda'],
            'code' => str_pad($data['id'],4,'0', STR_PAD_LEFT),
        ]);
        $contact = Contact::create(Arr::except($data, ['deuda','cuotas', 'interes', 'day','periodo','id']));
        $client->contact()->associate($contact);
        $client->save();
        setContable($client, '101', 'debit', $contact->name . ' ' . $contact->lastname, null, true);
        $this->addItem($data['deuda'],$data['interes'],$data['cuotas'], $client, $data['day'], $data['periodo']);
    }

    public function addItem($deuda, $interes, $cuotas, $client, $day, $periodo)
    {
        $data = [
            'product_id' => 1,
            'cost'=>0,

        ];
        $place=Place::first();
        $createInvoice = new CreateInvoice();
        $createInvoice->number = $place->id . '-' . str_pad($place->invoices()->withTrashed()->count() + 1, 7, '0', STR_PAD_LEFT);
        $createInvoice->setProduct('001');
        $createInvoice->form = $data;
        $createInvoice->cant =1;
        $createInvoice->price = $deuda;
        $createInvoice->discount = 0;
        $createInvoice->client =$client;
        $createInvoice->condition ='A Credito';
        $createInvoice->vence = Carbon::now()->addYear(1)->format('Y-m-d');
        $createInvoice->type ='B00';
        $createInvoice->confirmedAddItems();
        $createInvoice->sendInvoice();
        $invoice=Invoice::orderBy('id','desc')->first();
        $invoice->day=$day;
        $invoice->save();
        $order=new OrderConfirm();
        $order->invoice_id=$invoice->id;
        $order->modalOpened();
        $order->createCuota=1;
        $order->cuotas=$cuotas;
        $order->interes=$interes;
        $order->periodo=$periodo;
        $order->payInvoice();
    }
}
