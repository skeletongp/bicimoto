<?php

namespace App\Models;

use App\Traits\ContactTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Relacionado extends Model
{

    use HasFactory, SoftDeletes, ContactTrait;


    protected $guarded = [

    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
