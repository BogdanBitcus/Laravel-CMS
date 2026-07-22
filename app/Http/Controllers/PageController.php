<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Templates;

class PageController extends Controller
{
    public function showPage(Request $request, $link='') {

        $link = trim($link, '/');

        $page = Pages::getPageByAddr($link);
        if (!$page) {
            abort(404);
        }

        foreach ($page->options as $key => $value) {
            $page->{$key} = $value;
        }

        $template = Templates::getAdminTemplateByID($page->template);
        if (!$template) {
            abort(404);
        }

        switch ($page->template){
            case '2' : // About Us
                return view('views.'.$template->view_tpl, ['page'=>$page] );
                break;
            case '5' : // Contacts controller
                return app(ContactController::class)->index($request, $template, $page);

            default: // can be some...
                return view('views.'.$template->view_tpl, ['page'=>$page] );
        }

    }
}
