<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    //
    public function index()
    {
        return view('reports.index');
    }

    public function getUserReports()
    {
        $users = User::all();
        return view('reports.user_reports', compact('users'));
    }
}
