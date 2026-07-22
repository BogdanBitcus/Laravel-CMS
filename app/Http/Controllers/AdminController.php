<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pages;
use App\Models\Templates;
use Illuminate\Validation\Rule;
use App\Helpers\CmsHelper;


class AdminController extends Controller
{

    public function index(Request $request, Pages $page){

        $user = Auth::user();
        $list = $page->children()->orderBy('position')->get();
        $admin_teplate = Templates::getAdminTemplateByID($page->template);
        $templates = Templates::getTemplatesByParent($page->template);

        foreach ($page->options as $key => $value) {
            $page->{$key} = $value;
        }

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



    public function savePage(Request $request, Pages $page)
    {
        $pagesAddrNeedUpdate = [];
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'published'  => 'required|boolean',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pages')
                    ->where(function ($query) use ($page) {
                        return $query->where('parent', $page->parent);
                    })
                    ->ignore($page->id),
            ],
            'date'       => 'nullable|date',
            'image'      => 'nullable|string|max:255',
            'mobile_image' => 'nullable|string|max:255',
            'content'    => 'nullable|string',

            // SEO
            'seo_title'       => 'nullable|string|max:255',
            'seo_keywords'    => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);

        $slugChanged = $page->slug !== $validated['slug'];

        // custom fields with prefix 'custom_'
        $options = [];
        foreach ($request->all() as $key => $value) {

            if (str_starts_with($key, 'custom_') && $value !== '') {
                $options[$key] = $value;
            }
        }

        $page->fill($validated);
        $page->options = $options;
        $page->save();

        if ($slugChanged) {
            //CmsHelper::rebuildAddr($page);
            $pagesAddrNeedUpdate[] = $page->id;
        }

        $positions = $request->input('position', []);
        if (is_array($positions) && !empty($positions)) {

            asort($positions, SORT_NUMERIC);

            $children = Pages::whereIn('id', array_keys($positions))
                ->get()
                ->keyBy('id');

            $position = 0;
            foreach ($positions as $id => $value) {

                $position += 10;

                if (!isset($children[$id])) {
                    continue;
                }

                $child = $children[$id];

                $oldSlug = $child->slug;
                $newSlug = trim($request->input('slug_'.$id));

                $child->position = $position;
                $child->published = $request->boolean('published_'.$id);
                $child->slug = $newSlug;
                $child->name = trim($request->input('name_'.$id));
                $child->template = (int)$request->input('template_'.$id);

                $dirty = $child->isDirty();

                if ($dirty) {
                    $child->save();
                }

                if ($oldSlug !== $newSlug) {
                    //CmsHelper::rebuildAddr($child)
                    $pagesAddrNeedUpdate[] = $child->id;
                }

            }
        }

        CmsHelper::rebuildAddr($pagesAddrNeedUpdate);

        return redirect()
            ->route('cms.page.index', $page)
            ->with('message', __('Page Saved'));
    }



}