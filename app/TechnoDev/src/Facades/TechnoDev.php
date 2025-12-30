<?php

namespace App\TechnoDev\src\Facades;
use Illuminate\Support\Facades\Facade;

class TechnoDev extends Facade{

    protected static function getFacadeAccessor(){
        return "TechnoDev";
    }
}
