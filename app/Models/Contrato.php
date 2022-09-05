<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
   
}
