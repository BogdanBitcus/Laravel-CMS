<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\Pages;


class CmsHelper
{


    public static function rebuildAddr(array $rootIds): void
    {
        if (empty($rootIds)) {
            return;
        }

        /*
         |------------------------------------------------------------
         | Один SELECT
         |------------------------------------------------------------
         */
        $rows = DB::table('pages')
            ->select('id', 'parent', 'slug', 'addr')
            ->get()
            ->mapWithKeys(function($item){
                return [
                    $item->id => [
                        'parent'=>$item->parent,
                        'slug'=>$item->slug,
                        'addr'=>$item->addr,
                    ]
                ];
            })
            ->toArray();

        $children = [];
        foreach ($rows as $id => $page) {
            $children[$page['parent']][] = $id;
        }

        $updates = [];

        foreach ($rootIds as $rootId) {

            if (!isset($rows[$rootId])) {
                continue;
            }

            /*
             * Адреса батька
             */

            $parentAddr = '';

            $parentId = $rows[$rootId]['parent'];

            if ($parentId && isset($rows[$parentId])) {
                $parentAddr = $rows[$parentId]['addr'] ?: '';
            }

            self::updateBranch(
                $rootId,
                $parentAddr,
                $rows,
                $children,
                $updates
            );
        }

        /*
         * Оновлення пачками по 1000.
         */
        foreach (array_chunk($updates, 1000, true) as $chunk) {

            $cases = '';
            $ids = [];

            foreach ($chunk as $id => $addr) {

                $id = (int)$id;

                $cases .= "WHEN {$id} THEN " . DB::getPdo()->quote($addr) . ' ';

                $ids[] = $id;
            }

            DB::statement("
                UPDATE pages
                SET addr = CASE id
                    {$cases}
                END
                WHERE id IN (" . implode(',', $ids) . ")
            ");
        }
    }



    protected static function updateBranch(
        int $id,
        string $parentAddr,
        array &$rows,
        array &$children,
        array &$updates
    ): void {

        $addr = trim($parentAddr . '/' . $rows[$id]['slug'], '/');

        if ($rows[$id]['addr'] !== $addr) {

            $rows[$id]['addr'] = $addr;

            $updates[$id] = $addr;
        }

        if (empty($children[$id])) {
            return;
        }

        foreach ($children[$id] as $childId) {

            self::updateBranch(
                $childId,
                $addr,
                $rows,
                $children,
                $updates
            );
        }

    }










}