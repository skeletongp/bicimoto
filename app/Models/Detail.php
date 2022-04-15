<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'detailable_id',
        'detailable_type',
        'cant',
        'price',
        'total',
        'subtotal',
        'cost',
        'product_id',
        'unit_id',
        'utility',
        'user_id',
        'store_id',
        'place_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            Log::info('Detalle creado');
         
        });
        
    }

    public function detailable()
    {
        return $this->morphTo();
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'detail_taxes');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
