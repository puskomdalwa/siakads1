<?php
namespace App\Http\Services;

class ServiceNilai{
    public static function access(){
        if (!\Auth::user()) {
            return false;
        }

        if (\Auth::user()->level->level != 'Dosen') {
            return false;
        }

        return true;
    }
}