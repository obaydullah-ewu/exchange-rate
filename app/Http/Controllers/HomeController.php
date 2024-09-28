<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        $data['pageTitle'] = 'Home';
        return view('home')->with($data);
    }

    public function currencyDetails($currency_id)
    {
        $data['pageTitle'] = 'Currency Details';
        $data['currency_id'] = $currency_id;
        return view('currency-details')->with($data);
    }
}
