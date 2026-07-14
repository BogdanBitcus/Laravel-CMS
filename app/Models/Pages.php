<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;

    protected $table = 'pages';
    protected $fillable = [
        'parent',
        'position',
        'published',
        'template',
        'slug',
        'addr',
        'name',
        'date',
        'image',
        'mobile_image',
        'content',
        'options',
        'seo_title',
        'seo_keywords',
        'seo_description',
    ];


    public static function getPageById(int $id): ?self
    {
        return self::find($id);
    }


    public static function getPageByAddr($addr){
        $page = Pages::where('addr',$addr)->first();
        return $page;
    }



    public static function getPagesByParent($id){
        $pages = Pages::where('parent',$id)->orderBy('position')->get();
        return $pages;
    }



    public static function createPage($parent){
        return self::create([
            'parent' => $parent,
            'position' => 99999,
            'published' => 0
        ]);
    }



    public function deleteWithChildren(): bool
    {
        foreach ($this->children as $child) {
            $child->deleteWithChildren();
        }
        return $this->delete();
    }



    public function parentPage()
    {
        return $this->belongsTo(Pages::class, 'parent');
    }

    public function children()
    {
        return $this->hasMany(Pages::class, 'parent');
    }

    /*public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }*/



    public function breadcrumbs(): array
    {
        $items = [];
        $page = $this;
        while ($page) {
            array_unshift($items, $page);
            $page = $page->parentPage;
        }
        return $items;
    }



    /*public function makeAddr(): string
    {
        $parts = [];
        $page = $this;
        while ($page) {
            if (!empty($page->slug)) {
                array_unshift($parts, $page->slug);
            }
            $page = $page->parentPage;
        }
        return implode('/', $parts);
    }*/




}
