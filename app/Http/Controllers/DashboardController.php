<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request){

        $user = Auth::user();

        $page = Pages::getPageById(1);
        $list = Pages::getPagesByParent(1);

        return view('system.dashboard', ['user'=>$user, 'page'=>$page, 'list'=>$list]);
    }
}
