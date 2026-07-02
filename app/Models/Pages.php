<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;

    protected $table = 'pages';
    protected $fillable = ['parent','position','show'];


    public static function getPageById($id){
        //if($id==0) { abort(404); }
        $page = Pages::where('id',$id)->first();
        return $page;
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
            'show' => 0
        ]);
    }


    public static function removePage($id){
        return self::where('id', $id)->delete(); // forceDelete()
    }
}
