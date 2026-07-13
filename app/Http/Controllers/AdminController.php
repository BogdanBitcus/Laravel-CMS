<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pages;
use App\Models\Templates;


class AdminController extends Controller
{

    public function index(Request $request, Pages $page){

        $user = Auth::user();
        $list = $page->children()->orderBy('position')->get();
        $admin_teplate = Templates::getAdminTemplateByID($page->template);
        $templates = Templates::getTemplatesByParent($page->template);

        return view(
            'edits.'.$admin_teplate->admin_tpl,
            [
                'user'=>$user,
                'page'=>$page,
                'list'=>$list,
                'templates'=>$templates,
                'breadcrumbs' => $page->breadcrumbs(),
            ]
        );
    }



    public function createPage(Pages $page) {
        $p = Pages::createPage($page->id);
        return redirect()->route('cms.page.index', $page);
    }



    public function deletePage(Pages $page){
        $page->deleteWithChildren();
        return response()->json(['message' => __('Page deleted successfully')]);
    }



    public function savePage(Request $request, Pages $page){

    }

}