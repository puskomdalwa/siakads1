<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller {
	
    public function index(Request $request){       
        dd(Auth::user()->level);
    }
}
