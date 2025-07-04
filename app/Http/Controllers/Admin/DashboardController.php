<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $page = 'admin/dashboard';
        $title = 'Dashboard';
        $js = 'dashboard';
        return view('admin/layout', compact('page', 'title', 'js'));
    }
}
