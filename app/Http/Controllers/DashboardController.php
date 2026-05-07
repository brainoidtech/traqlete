<?php

namespace App\Http\Controllers;

class DashboardController1 extends Controller
{
    public function index()
    {
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);

        return view('pages/dashboards.index');
    }

}
