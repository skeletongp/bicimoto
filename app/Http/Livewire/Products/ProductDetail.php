<?php

namespace App\Http\Livewire\Products;

use App\Http\Helper\Universal;
use App\Models\Product;
use Illuminate\Support\Collection;
use Livewire\Component;

class ProductDetail extends Component
{
    public Product $product;
    public function render()
    {
        $price= $this->formatedData();
        return view('livewire.products.product-detail', compact('price'));
    }
    public function formatedData() : Collection
    {
        $price=$this->product->units->pluck('plainPrice','name');
        $taxes=$this->product->taxes->pluck('rate','name');
        $wTax=[];
        foreach($price as $in=> $priz){
          
            $price[$in]='$'.Universal::formatNumber($priz);
            foreach ($taxes as $ind=> $tax) {
                $wTax[$ind]=$priz*$tax;
            }
            $price[$in]=$price[$in].'+$'.Universal::formatNumber((array_sum($wTax)));
        }
        return $price;
    }
}
