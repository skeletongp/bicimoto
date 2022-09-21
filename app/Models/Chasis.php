<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chasis extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = "mysql";
    protected $table = "chasis";
    protected $guarded = [];
    static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $place=getPlace();
            $model->code = mb_substr($model->tipo, 0, 1) . mb_substr($model->color, 0, 1).'-' . date('md').'-' . $model->id;
            $model->place_id = $place->id;
            $model->save();
        });
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
