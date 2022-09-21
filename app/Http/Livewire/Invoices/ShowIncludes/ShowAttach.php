<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use App\Models\Store;
use Illuminate\Support\Facades\App;
use NumberFormatter;

trait ShowAttach
{
    public $client, $clients, $client_code;
    public function loadDocuments()
    {
        $contrato = $this->invoice->contrato;
        if($contrato){
            $date = date_create($contrato->invoice->day);
        $meses = ['January' => 'ENERO', 'february' => 'FEBRERO', 'March' => 'MARZO', 'May' => 'MAYO', 'June' => 'JUNIO', 'July' => 'JULIO', 'August' => 'AGOSTO', 'September' => 'SEPTIEMBRE', 'October' => 'OCTUBRE', 'November' => 'NOVIEMBRE', 'December' => 'DICIEMBRE'];
        $f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
        $año = $f->format(date_format($date, 'Y'));
        $dia = $f->format(date_format($date, 'd'));
        $cuota = $contrato->invoice->cuotas()->first();
        $cuota->fecha = date_create($cuota->fecha);
        $store = optional(auth()->user())->store ?: Store::find(env('STORE_ID'));
        $user = $store->users()->where('users.id', '!=', 1)->first();
        $data = [
            'invoice' => $contrato->invoice,
            'client' => $contrato->client,
            'store' => Store::find(env('STORE_ID')),
            'dia' => $dia,
            'mes' => $meses[date_format($date, 'F')],
            'año' => $año,
            'cuotaDia' => $f->format(date_format($cuota->fecha, 'd')),
            'cuotaMes' => $meses[date_format($cuota->fecha, 'F')],
            'cuotaAño' => $f->format(date_format($cuota->fecha, 'Y')),
            'date' => $date,
            'user' => $user,
            'contrato' => $contrato,
            'cuota' => $cuota,
            'contact' => $contrato->client->contact,
            'f' => $f,
        ];
        $PDF = App::make('dompdf.wrapper');
        $pdf = $PDF->setOptions([
            'logOutputFile' => null,
            'isRemoteEnabled' => true
        ])->loadView('pages.clients.contrato-pdf', $data);
        //delete file if exists
        $this->document = base64_encode($pdf->output());
        }else{
            $this->document = null;
        }
        
        
        
    }
}
