<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pages;
use App\Models\Templates;


class AdminController extends Controller
{

    public function index(Request $request, Pages $id){

        $user = Auth::user();
        $page = Pages::getPageById($id->id);
        $list = Pages::getPagesByParent($id->id);
        $admin_teplate = Templates::getAdminTemplateByID($page->template);

        return view('edits.'.$admin_teplate->admin_tpl, ['user'=>$user, 'page'=>$page, 'list'=>$list]);
    }



    public function createPage($parent) {

    }


    public function savePage(Request $request, Pages $id){

    }


    public function deletePage(){

    }

}