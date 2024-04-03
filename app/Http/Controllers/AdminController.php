<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pages;
use App\Models\Types;


class AdminController extends Controller
{

    public function index(Request $request, $id){

        $user = Auth::user();
        $id = $request->route('id');
        $page = Pages::getPageById($id);
        $list = Pages::getPagesByParent($id);
        $admin_teplate = Types::getAdminTemplateByID($page->type);

        return view('edits.'.$admin_teplate->admin_tpl, ['user'=>$user, 'page'=>$page, 'list'=>$list]);
    }



    public function editPage($lang, $id) {
        // Логіка для адмінки
    }
}