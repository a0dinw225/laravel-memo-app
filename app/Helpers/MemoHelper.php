<?php

namespace App\Helpers;

use App\Models\Tag;

class MemoHelper
{
    /**
     * Display memos
     *
     * @param object $memos
     * @param integer $tagId
     * 
     */
    public static function displayMemos($memos, $tagId)
    {
        if (is_null($memos) && is_null($tagId)) {
            return;
        }

        if ($tagId && $memos->isEmpty()) {
            $tagName = self::getTagName($tagId);
            $res = '関連づけられたメモがありません<br>';
            $res .= "タグ名 : {$tagName} を<a href='#' onclick='deleteTag({$tagId}); return false;'>削除</a>";
            return $res;
        }

        $output = '';
        foreach ($memos as $memo) {
            $output .= '<a href="/edit/' . $memo['id'] . '" class="card-text d-block text-decoration-none elipsis mb-2">' . $memo['content'] . '</a>';
        }

        return $output;
    }

    /**
     * Get tag name
     *
     * @param integer $tagId
     * @return string
     */
    public static function getTagName($tagId): string
    {
        $tag = Tag::find($tagId);
        return $tag ? $tag->name : '';
    }
}
