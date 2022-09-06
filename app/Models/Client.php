<?php

namespace App\Models;

use App\Observers\ClientObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Nicolaslopezj\Searchable\SearchableTrait;

class Client extends Model implements Searchable
{
    use HasFactory, SoftDeletes, SearchableTrait;

    protected $connection = "mysql";

    protected $with = ['contact', 'store'];

    protected $guarded = [
        
    ];
    
   

    public function getSearchResult(): SearchResult
    {
        $url = route('clients.show', $this->id);
        return new SearchResult(
            $this,
            $this->contact->fullname,
            $url
        );
    }
    function getPuntajeAttribute()
    {
        $puntaje = 0;
        $cred=$this->crediticio;
        if($cred){
            $state=$cred->state/12;
            $mueble=$cred->mueble/12;
            $saldo=$cred->bank_value/12;
            $salary=$this->laboral->salary;
            $incomes=$state+$mueble+$saldo+$salary;
            $outcomes=$cred->rent+$cred->hipoteca+$cred->loans+$cred->others;
            $outcomes=$outcomes?:0.000000001;
            $incomes=$incomes?:0.000000001;
           // dd($incomes,$salary,$puntaje);
            $puntaje=1-($outcomes/$incomes);
            $puntaje*=100;
        }
        
        return $puntaje;
    }

    public static function boot()
    {
        parent::boot();
        self::observe(new ClientObserver);
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function avatar(): Attribute
    {
        return new Attribute(
            get: fn () => $this->image ? $this->image->path : env('NO_IMAGE')
        );
    }
    public function balance(): Attribute
    {
        return new Attribute(
            get: fn () => formatNumber($this->limit)
        );
    }

   
    public function contable()
    {
        $place_id = 1;
        if (auth()->user()) {
            $place_id = auth()->user()->place->id;
        }
        return $this->morphOne(Count::class, 'contable')->where('place_id', $place_id);
    }
    function counts()
    {
        $place_id = 1;
        if (auth()->user()) {
            $place_id = auth()->user()->place->id;
        }
        return $this->morphMany(Count::class, 'contable')->where('place_id', $place_id);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payer');
    }
    public function getDebtAttribute()
    {
        return optional($this->invoices)->sum('rest');
    }
    public function pdfs()
    {
        return $this->morphMany(Filepdf::class, 'fileable');
    }
    public function contrato($id)
    {
        return $this->morphOne(Filepdf::class, 'fileable')->where('reference_id', $id)->first();
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    function contact()
    {
        return $this->belongsTo(Contact::class);
    }
     function conyuge()
    {
        return $this->hasOne(Conyuge::class);
    }
    function laboral()
    {
        return $this->hasOne(Laboral::class);
    }
    function crediticio()
    {
        return $this->hasOne(Crediticio::class);
    }
    function transactions()
    {
        $counts = $this->counts()->pluck('id');
        $place_id = 1;
        if (auth()->user()) {
            $place_id = auth()->user()->place->id;
        }
        $place = Place::find($place_id);
        return $place->transactions()->whereIn('creditable_id', $counts)->orWhereIn('debitable_id', $counts)->orderBy('created_at', 'desc');
    }
    public function cuotas(){
        return $this->hasMany(Cuota::class);
    }
    public function sendCatalogue()
    {
        $path=Cache::get('productCatalogue_'.env('STORE_ID'));
        Log::info($path);
        if(!$path){
            $path="https://atriontechsd.nyc3.digitaloceanspaces.com/files2/cat%C3%A1logo/catalogo%20de%20productos.pdf";
        }
        sendWSCatalogue($this->contact->cellphone, $path);
    }
}