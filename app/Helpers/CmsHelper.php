<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\Pages;


class CmsHelper
{


    public static function makeNull($id)
    {
        Pages::where('id', $id)->update(['addr' => '']);

        $pages = Pages::where('parent', $id)->get();

        foreach ($pages as $page) {
            self::makeNull($page->id);
        }
    }



    public static function makeAddr()
    {
        $noAddrIds = Pages::where('addr', '')->pluck('id')->toArray();

        if(!empty($noAddrIds)) {
            foreach ($noAddrIds as $id) {
                $urls = [];
                $url = Pages::where('id', $id)->select('url', 'parent')->first();

                while ($url) {
                    if ($url['url'] != '') {
                        $urls[] = $url['url'];
                    }
                    $url = Pages::where('id', $url['parent'])->select('url', 'parent')->first();
                }

                $urlsReversed = array_reverse($urls);
                $addr = implode("/", array_filter($urlsReversed));

                Pages::where('id', $id)->update(['addr' => $addr]);
                //Pages::where('id', $id)->update(['addr' => $addr.'/']);
            }
        }
    }



}