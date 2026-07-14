<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\Pages;


class CmsHelper
{

    public static function rebuildAddr(Pages $page): void
    {
        $page->loadMissing('parentPage', 'childrenRecursive');

        self::updateBranch($page, $page->parentPage?->addr ?? '');
    }


    protected static function updateBranch(Pages $page, string $parentAddr): void
    {
        $addr = trim($parentAddr . '/' . $page->slug, '/');

        if ($page->addr !== $addr) {
            $page->addr = $addr;
            $page->save();
        }

        foreach ($page->childrenRecursive as $child) {
            self::updateBranch($child, $addr);
        }
    }





}