<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Chasis;
use App\Models\Place;
use App\Models\User;

trait DetailsSectionTrait
{
    public $producto;
    public $product, $product_code, $product_name, $products, $stock, $unit, $open = false;
    public $pivot_id;
    public $chasis, $facturable = true;
    function rules()
    {
        return invoiceCreateRules();
    }

    public function setProduct($product_code)
    {
        $code = str_pad($product_code, 3, '0', STR_PAD_LEFT);
        $place = optional(auth()->user())->place ?: Place::first();
        $chasis = Chasis::where('code', trim($product_code))
            ->where('place_id', $place->id)
            ->where('status', 'Pendiente')
            ->first();
        $product = nulL;
        if ($chasis) {
            $product = $chasis->product;
            $this->chasis = $chasis;
            $this->product_code = $product->code;
            $this->render();
        }
        if (!$product) {
            $product = $place->products()->where('code', $code)->first();
        }
        $this->producto = $product;
        if ($product) {
            if($product->chasis()->count() && !$chasis){
                $this->emit('showAlert', 'Ingrese el código de Chasis del producto');
                $this->product_code = '';
                return ;
            }
            $productLoad = [
                'name' => $product->name,
                'code' => $product->code,
                'units' => $product->units,
                'type' => $product->type,
                'taxes' => $product->taxes()->pluck('taxes.id')
            ];
            $this->form['product_id'] = $product->id;
            $this->unit_id = $product->units->first()->pivot->id;
            $this->product = collect($productLoad);
            $this->product_code = $code;
            $this->product_name = $product->name;
            $this->freshUnitId();
        } else {
            $this->reset('form', 'product', 'cant', 'product_code', 'price', 'discount', 'total', 'product_name', 'taxTotal');
        }
    }
    public function tryAddItems()
    {
        $this->validate(['product' => 'required']);

        if ($this->cant > $this->stock && !auth()->user()->hasPermissionTo('Autorizar') && $this->product['type'] != 'Servicio') {
            $this->authorize('Vender producto fuera de Stock', 'validateAuthorization','confirmedAddItems','data=null','Autorizar');
        } else {
        $this->confirmedAddItems();
         }
        if ($this->chasis) {
            $this->facturable = false;
        };
        $this->emit('focusCode');
    }

    public function updatedCant()
    {
        if ($this->producto) {
            $unt = $this->producto->units()->where('units.id', $this->unit->id)->first()->pivot;
            $min = $unt->min;
            if ($this->client && $this->client['special']) {
                $this->price = $unt->price_special;
            } elseif ($this->condition == 'A Credito') {
                $this->price = $unt->price_mayor;
            } else {
                $this->price = $unt->price_menor;
            }
            $pr = removeComma($this->price);
            $sub = removeComma(formatNumber((floatVal($this->cant)  * $pr) * (1 - ($this->discount / 100))));
            if ($this->product) {
                $this->taxTotal = $sub * $this->producto->taxes->sum('rate');
                $this->checkStock();
            }
            $this->total = removeComma(formatNumber($sub + $this->taxTotal));
        }
    }
    public function confirmedAddItems()
    {
        $user = optional(auth()->user()) ?: User::first();
        $this->price = str_replace(',', '', $this->price);
        $this->form['id'] = count($this->details);
        $this->form['cant'] = $this->cant;
        $this->form['price'] = str_replace(',', '', $this->price);
        $this->validate();
        $this->form['subtotal'] =  operate($this->cant, '*', $this->price);
        $this->form['discount_rate'] =  ($this->discount / 100);
        $this->form['discount'] = (operate($this->form['subtotal'], '*', ($this->discount / 100)));
        $this->form['taxTotal'] = $this->taxTotal;
        $this->form['total'] = ($this->form['subtotal'] - $this->form['discount']) + $this->taxTotal;
        $this->form['utility'] = ($this->form['cant'] * $this->form['price']) - ($this->form['cant'] * $this->form['cost']);
        $this->form['unit_id'] = $this->unit->id;
        $this->form['unit_pivot_id'] = $this->pivot_id;
        $this->form['user_id'] = $user->id;
        $this->form['store_id'] = env('STORE_ID');
        $this->form['place_id'] = optional($user->place)->id ?: 1;
        $this->form['product_name'] = $this->product['name'];
        $this->form['product_code'] = $this->product['code'];
        $this->form['taxes'] = $this->product['taxes'];

        array_push($this->details, $this->form);
        $this->emit('focusCode');
        $this->reset('form', 'product', 'cant', 'product_code', 'price', 'discount', 'total', 'product_name', 'taxTotal');
    }
    public function removeItem($id)
    {
        //dd($id, $this->details);
        $this->form['product_id'] = $this->details[$id]['product_id'];
        unset($this->details[$id]);
        $this->details = array_values($this->details);
        if (count($this->details)) {
            foreach ($this->details as $ind => $det) {
                $this->details[$ind]['id'] = $ind;
            }
        }
        $this->checkStock();
    }
    public function editItem($id)
    {
        $this->form = $this->details[$id];
        $this->product_code = $this->form['product_code'];
        $this->setProduct($this->product_code);
        $this->product_name = $this->form['product_name'];
        $this->cant = $this->form['cant'];
        $this->price = $this->form['price'];
        $this->discount = ($this->form['discount'] / ($this->form['cant'] * $this->form['price'])) * 100;
        $this->total = $this->form['total'];
        $this->taxTotal = $this->form['taxTotal'];
        $this->unit_id = $this->form['unit_pivot_id'];
        $this->pivot_id = $this->form['unit_pivot_id'];
        $this->removeItem($id);
        $this->emit('focusCode');
        $this->facturable = true;
    }

    public function checkStock()
    {
        $exist = array_keys(array_column($this->details, 'product_id'), $this->form['product_id']);
        if ($exist) {
            foreach ($exist as $key) {
                if ($this->details[$key]['unit_id'] == $this->unit->id) {
                    $this->stock = $this->stock - $this->details[$key]['cant'];
                }
                $this->details = array_values($this->details);
            }
        }
    }
    public function freshUnitId()
    {
        $place = optional(auth()->user())->place ?: Place::first();
        $unit = $place->units()->wherePivot('id', $this->unit_id)->first();
        if ($unit) {
            $this->unit = $unit;
            $this->price = $unit->pivot->price_menor;
            $this->stock = $unit->pivot->stock;
            if ($this->client && $this->client['special']) {
                $this->price = $unit->pivot->price_special;
                $this->form['price_type'] = 'detalle';
            } else if ($this->cant >= $unit->pivot->min) {
                $this->price = $unit->pivot->price_mayor;
                $this->form['price_type'] = 'mayor';
            } else {
                $this->price = $unit->pivot->price_menor;
                $this->form['price_type'] = 'detalle';
            }


            $this->form['unit_name'] = $unit->symbol;

            $pr = removeComma($this->price);
            $sub = removeComma(formatNumber((floatVal($this->cant)  * $pr) * (1 - ($this->discount / 100))));
            if ($this->product) {
                $this->form['cost'] = $unit->pivot->cost;
                $this->taxTotal = (floatVal($this->cant)  * $pr) * $this->producto->taxes->sum('rate');
                $this->checkStock();
            }
            $this->total = removeComma(formatNumber($sub + $this->taxTotal));
            $this->pivot_id = $unit->pivot->id;
        }
    }
    public function updatedProductCode()
    {
        $code = substr($this->product_code, 0, 3);
        $this->setProduct($code);
    }
    public function updatedProductName()
    {
        $code = substr($this->product_name, 0, 3);
        $this->setProduct($code);
    }
    public function updatingPrice($newPrice)
    {
        $oldPrice = floatVal($this->price) ?: 0.0001;
        $this->freshUnitId();


        $pr = removeComma($newPrice);
        $sub = removeComma(formatNumber((floatVal($this->cant)  * $pr) * (1 - ($this->discount / 100))));
        if ($this->product) {

            $this->taxTotal = $sub * $this->producto->taxes->sum('rate');
            $this->checkStock();
        }
        $this->total = str_replace(',', '', formatNumber($sub + $this->taxTotal));
        $this->price = $newPrice;
    }

    public function updatingDiscount($desc)
    {
        if ($desc && !is_nan($desc)) {
            $this->discount = $desc * 100;
        }
    }
}
