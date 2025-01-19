<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ServiceAuth
{
    public static function logout()
    {
        $idUserErrors = [28, 5916, 10722, 10761, 10762, 2, 8483, 10760];
        if (in_array(Auth::user()->id, $idUserErrors)) {
            Auth::logout();
            Session::flush();
            return redirect()->route('login');
        }else {
            return false;
        }
    }
}
