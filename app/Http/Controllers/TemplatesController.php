<?php

namespace App\Http\Controllers;

use App\Models\Templates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplatesController extends Controller
{
    public function index(Request $request){

        $user = Auth::user();
        $templates = self::getTemplates(0);

        return view('system.templates', ['user'=>$user, 'templates'=>$templates]);
    }


    function getTemplates($parent) {
        $children = Templates::where('parent', $parent)->get();
        foreach ($children as $child) {
            $child->children = self::getTemplates($child->id);
        }
        return $children;
    }


    public function templateEdit($id){

        $user = Auth::user();
        $template = Templates::where('id', $id)->first();
        $view_tpl = array();
        $admin_tpl = array();

        $view_files = scandir(__DIR__.'/../../../resources/views/views/');
        //$view_files = scandir($_SERVER['DOCUMENT_ROOT'].'/../resources/views/views/');
        if(is_array($view_files))
            foreach($view_files as $f){
                if (preg_match("/^view_*/",$f)){

                    $value = str_replace(".blade.php", "", $f);

                    if($template->view_tpl.'.blade.php' == $f){
                        $selected = 1;
                    } else {
                        $selected = 0;
                    }

                    $view_tpl[] = [
                        'value' => $value,
                        'selected' => $selected,
                        'filename' => $f
                    ];
                }
            }

        $edit_files = scandir(__DIR__.'/../../../resources/views/edits/');
        //$edit_files = scandir($_SERVER['DOCUMENT_ROOT'].'/../resources/views/edits/');
        if(is_array($edit_files))
            foreach($edit_files as $f){
                if (preg_match("/^edit_*/",$f)){

                    $value = str_replace(".blade.php", "", $f);

                    if($template->admin_tpl.'.blade.php' == $f){
                        $selected = 1;
                    } else {
                        $selected = 0;
                    }

                    $admin_tpl[] = [
                        'value' => $value,
                        'selected' => $selected,
                        'filename' => $f
                    ];
                }
            }

        $templates = self::getTemplates(0);

        return view('system.template_edit', ['user'=>$user, 'template'=>$template, 'view_tpl'=>$view_tpl, 'admin_tpl'=>$admin_tpl, 'templates'=>$templates]);
    }




    public function templateSave(Request $request, $id){

        $query = Templates::where('id', $id)->update([
            'parent' => $request->input('parent'),
            'name' => $request->input('name'),
            'view_tpl' => $request->input('view_tpl'),
            'admin_tpl' => $request->input('admin_tpl'),
        ]);

        return redirect('/cms/templates')->with('message',__('Template saved'));
    }


    public function addTemplate(){
        $template = Templates::createTemplate();
        return redirect('/cms/templates/'.$template->id);
    }


    public function templateDelete($id){
        $page = Templates::removePage($id);
        return response()->json(['message' => __('Template deleted successfully')]);
    }

}
