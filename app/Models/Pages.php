<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;

    protected $table = 'pages';

    public static function getPageById($id){
        $page = Pages::where('id',$id)->first();
        return $page;
    }

    public static function getPagesByParent($id){
        $pages = Pages::where('parent',$id)->get();
        return $pages;
    }
}
