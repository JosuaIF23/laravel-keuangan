<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function keuangan()
    {
        return view('pages.app.home');
    }

    public function keuanganDetail()
    {
        return view('pages.app.keuangan.detail');
    }
}
