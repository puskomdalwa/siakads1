<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RootController extends Controller
{
    public function index(Request $request)
    {
        // $queryParameters = $request->query();

        // if (!empty($queryParameters)) {
        //     // Handle the case where the URL has query parameters
        //     return redirect()->route('root');
        // }
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }
}