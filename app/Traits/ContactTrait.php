<?php
namespace App\Traits;

trait ContactTrait
{
    public function getFullNameAttribute()
    {
        if(!$this->contact){
            return nulL;
        }
        return $this->contact->name.' '.$this->contact->lastname;
    }
    
}