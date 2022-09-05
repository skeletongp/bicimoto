<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuota extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded =[];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
