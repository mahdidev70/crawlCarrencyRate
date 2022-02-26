<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Carbon\Carbon;

class FirstController extends Controller
{

    public function currentUsd()
    {
        $price = Price::latest()->first();
        return response(['USD' => $price->usd]);
    }

    public function currentEuro()
    {
        $price = Price::latest()->first();
        return response(['EURO' => $price->eur]);
    }


    public function todayUsd()
    {
        $price = Price::select('usd', 'created_at')
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();
        return response(['USD' => $price]);
    }

    public function todayEuro()
    {
        $price = Price::select('eur', 'created_at')
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();
        return response(['EURO' => $price]);
    }

    public function percent()
    {
        $price = Price::select('percent', 'created_at')->get();
        return response(['PERCENT' => $price]);
    }
}
