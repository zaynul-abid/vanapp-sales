<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function superadminIndex(){
        return view('dashboard.pages.superadmin.dashboard');
    }

    public function adminIndex(){
        return view('dashboard.pages.admin.dashboard');
    }


    public function employeeIndex(){

        return view('dashboard.pages.employee.dashboard');
    }
}
