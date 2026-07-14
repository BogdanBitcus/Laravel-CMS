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
        if ( ! $user->is_admin ) {
            Auth::logout();
            return redirect()->route('cms.auth')->with('error', __('You do not have permission to access this page'));
        }
        $page = Pages::getPageById(1);
        $list = Pages::getPagesByParent(1);
        $templates = Templates::getTemplatesByParent(0);

        return view('system.dashboard', ['user'=>$user, 'page'=>$page, 'list'=>$list, 'templates'=>$templates]);
    }



    public function addPage()
    {
        $page = Pages::createPage(1); // Create page with parent=1 at dashboard
        return redirect()->route('cms.dashboard.index');
    }



    public function save(Request $request)
    {
        $positions = $request->input('position', []);
        if (is_array($positions) && !empty($positions)) {

            asort($positions, SORT_NUMERIC);

            $pages = Pages::whereIn('id', array_keys($positions))
                ->get()
                ->keyBy('id');

            $position = 0;
            foreach ($positions as $itemId => $value) {

                $position += 10;

                if (!isset($pages[$itemId])) {
                    continue;
                }

                $page = $pages[$itemId];

                $oldSlug = $page->slug;

                $page->position = $position;
                $page->published = $request->boolean('published_'.$itemId);
                $page->slug = trim($request->input('slug_'.$itemId));
                $page->name = trim($request->input('name_'.$itemId));
                $page->template = (int)$request->input('template_'.$itemId);

                $page->save();

                if ($oldSlug !== $page->slug) {
                    CmsHelper::rebuildAddr($page);
                }
            }
        }

        return redirect()
            ->route('cms.dashboard.index')
            ->with('message', __('Page Saved'));
    }



    public function deletePage(Pages $page)
    {
        $page->deleteWithChildren();
        return response()->json(['message' => __('Page deleted successfully')]);
    }



}
