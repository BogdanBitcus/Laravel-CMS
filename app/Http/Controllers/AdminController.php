<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pages;
use App\Models\Templates;


class AdminController extends Controller
{

    public function index(Request $request, $id){

        $user = Auth::user();
        if ( ! $user->is_admin ) {
            Auth::logout();
            return redirect('/cms')->with('error', __('You do not have permission to access this page'));
        }

        $id = $request->route('id');
        $page = Pages::getPageById($id);
        $list = Pages::getPagesByParent($id);
        $admin_teplate = Templates::getAdminTemplateByID($page->template);

        return view('edits.'.$admin_teplate->admin_tpl, ['user'=>$user, 'page'=>$page, 'list'=>$list]);
    }



    public function editPage($lang, $id) {
        // Логіка для адмінки
    }
}