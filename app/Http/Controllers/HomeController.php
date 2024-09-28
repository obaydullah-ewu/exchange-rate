<?php

namespace App\Http\Controllers;

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
