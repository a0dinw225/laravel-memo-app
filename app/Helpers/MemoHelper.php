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
     * @return string
     */
    public static function displayMemos($memos, $tagId): string
    {
        if (is_null($memos) && is_null($tagId)) {
            return '';
        }

        if ($tagId && $memos->isEmpty()) {
            $tagName = self::getTagName($tagId);
            $res = '<div class="alert alert-warning" role="alert">';
            $res .= '関連づけられたメモがありません';
            $res .= "</div>";
            $res .= "<div class='text-center'>";
            $res .= "<p>タグ名 : <strong>{$tagName}</strong></p>";
            $res .= "<button class='btn btn-danger' onclick='deleteTag({$tagId}); return false;'>タグを削除</button>";
            $res .= "</div>";
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
