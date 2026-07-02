<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Templates;

class PageController extends Controller
{
    public function showPage($link='') {

        $link = trim($link, '/');

        $page = Pages::getPageByAddr($link);
        if (!$page) {
            abort(404);
        }

        $template = Templates::getAdminTemplateByID($page->template);
        if (!$template) {
            abort(404);
        }

        switch ($page->template){
            case '2' : // About us
                break;
            case '3' : // News list
                break;
            case '4' : // New
                break;
            case '5' : // Gallery
                break;
            case '6' : // Contacts
                break;
            default: // can be some...
                break;
        }




        return view('views.'.$template->view_tpl, ['page'=>$page] );
    }
}
