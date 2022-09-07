<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\ProductPlaceUnit;
use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $place=Place::first();
        $store=Store::find(env('STORE_ID'));
       $this->createBanks($place, $store);
        $this->createdProducts($place, $store);
        //$this->createComprobantes($store);
       
    }
    public function createComprobantes($store)
    {
        for ($i=1; $i < 21; $i++) { 
            $store->comprobantes()->create([
                'type'=>'COMPROBANTE DE CONSUMIDOR FINAL',
                'prefix'=>'B02',
                'number'=> str_pad($i, 8,'0', STR_PAD_LEFT),
            ]);
        }
    }
    public function createdProducts($place, $store)
    {
        $prestamo=$store->products()->create([
            'name'=>'Financimiento',
            'code'=>'001',
            'type'=>'Servicio'
        ]);
        $this->assignUnit(1,$place, $prestamo, 50000,50000,50000,1,0);

    }
    public function assignUnit($unit_id,$place,$product, $price_mayor, $price_menor, $special, $min, $cost){
        ProductPlaceUnit::create([
            'product_id'=>$product->id,
            'place_id'=>$place->id,
            'unit_id'=>$unit_id,
            'price_mayor'=>$price_mayor,
            'price_menor'=>$price_menor,
            'price_special'=>$special,
            'min'=>$min,
            'cost'=>$cost,
            'margin'=>1,
        ]);
    }
    public function createBanks($place, $store)
    {
        $popular=$store->banks()->create([
            'bank_name'=>'Banco Popular',
            'bank_number'=>'803579804',
            'titular'=>'Ismael Contreras',
        ]);
        $reservas=$store->banks()->create([
            'bank_name'=>'BanReservas',
            'bank_number'=>'3604789684',
            'titular'=>'Ismael Contreras',
        ]);

        setContable($popular,'100','debit', $popular->bank_name, $place->id,false);
        setContable($reservas,'100','debit', $reservas->bank_name, $place->id,false);
    }
}
