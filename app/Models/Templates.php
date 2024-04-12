<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Templates extends Model
{
    use HasFactory;

    protected $table = 'templates';
    protected $fillable = ['parent','name'];



    public static function getAdminTemplateByID($id)
    {
        $template = Templates::where('id',$id)->first();
        return $template;
    }



    public static function getTemplatesByParent($parent)
    {
        $types = Templates::where('parent', $parent)->get();
        return $types;
    }



    public  static function createTemplate(){
        return self::create([
            'parent' => 0,
            'name' => 'New Template',
        ]);
    }



    public static function removePage($id)
    {
        return self::where('id', $id)->delete(); // forceDelete()
    }



}
