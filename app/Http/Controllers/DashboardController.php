<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use App\Models\Templates;
use App\Helpers\CmsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{


    public function index(Request $request){

        $user = Auth::user();

        $page = Pages::getPageById(1);
        $list = Pages::getPagesByParent(1);
        $templates = Templates::getTemplatesByParent(0);

        return view('system.dashboard', ['user'=>$user, 'page'=>$page, 'list'=>$list, 'templates'=>$templates]);
    }



    public function addpage()
    {
        $page = Pages::createPage(1); // Create page with parent=1 at dashboard
        return redirect('/cms/dashboard');
    }



    public function save(Request $request)
    {
        $positions = $request->input('position', []);
        if(is_array($positions)){
            asort($positions, SORT_NUMERIC);
            reset($positions);
            $o=0;
            foreach($positions as $itemId => $position){
                $o+=10;
                $query = Pages::where('id', $itemId)->update([
                    'position' => $o,
                    'show' => $request->input('show_' . $itemId),
                    'url' => $request->input('url_'  . $itemId),
                    'name' => $request->input('name_' . $itemId),
                    'template' => $request->input('template_' . $itemId),
                ]);
                $addr = $request->input('addr_' . $itemId);
                if($addr == '') {
                    CmsHelper::makeNull($itemId);
                }
            }
        }

        CmsHelper::makeAddr();

        return redirect('/cms/dashboard')->with('message',__('Page Saved'));
    }



    public function deletepage($id)
    {
        $page = Pages::removePage($id);
        return response()->json(['message' => __('Page deleted successfully')]);
    }



}
