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



    public static function getPagesByParent($id, $published=1){

        $pages = Pages::where('parent',$id);

        if($published != 'all'){
            $pages->where('published',$published);
        }

        return $pages->orderBy('position')->get();
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



    public function getOptionsAttribute($value): array
    {
        return $value ? unserialize($value, ['allowed_classes' => false]) ?: [] : [];
    }


    public function setOptionsAttribute($value): void
    {
        $this->attributes['options'] = empty($value) ? null : serialize($value);
    }


    public function __get($key)
    {
        if (str_starts_with($key, 'custom_')) {
            return $this->options[$key] ?? null;
        }

        return parent::__get($key);
    }


    public function __set($key, $value)
    {
        if (str_starts_with($key, 'custom_')) {

            $options = $this->options;
            $options[$key] = $value;
            $this->options = $options;

            return;
        }

        parent::__set($key, $value);
    }



}
