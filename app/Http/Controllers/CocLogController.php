<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CocLogController extends Controller
{
    public function index()
    {
        return view('hr.CTO.coclog');
    }

    public function showCocLogs($id)
    {
        $coc = CocLog::find($id);
        return view('hr.CTO.show_coc', compact('coc'));
    }
}
