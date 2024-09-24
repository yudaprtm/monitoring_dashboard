<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Query;

class DashboardController extends Controller
{
    public function showDashboard(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $datas = Content::where('table_name', 'LIKE', '%' . $search . '%')->get();
        } else {
            $datas = Content::all();
        }

        $impala = Query::where('type', 'impala')->get();
        $hive = Query::where('type', 'hive')->get();

        return view('dashboard.dashboard', [
            'datas' => $datas,
            'impala' => $impala,
            'hive' => $hive
        ]);
    }
}
