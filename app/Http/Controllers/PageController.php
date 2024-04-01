<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function showPage($lang, $link) {
        $fullLink = // Отримати повне посилання з бази даних
        $page = // Отримати дані сторінки за повним посиланням
        return view('pages.'.$page->file, compact('page'));
    }
}
