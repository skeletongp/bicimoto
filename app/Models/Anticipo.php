<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anticipo extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $connection = "mysql";

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
