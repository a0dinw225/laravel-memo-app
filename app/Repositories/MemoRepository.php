<?php

namespace App\Repositories;

use App\Models\Memo;

class MemoRepository
{
    protected $memo;

    public function __construct(Memo $memo)
    {
        $this->memo = $memo;
    }

    public function getUserMemoWithTagsById(int $id, int $userId): array
    {
        return $this->memo
                ->join('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
                ->join('tags', 'memo_tags.tag_id', '=', 'tags.id')
                ->where('memos.user_id', $userId)
                ->where('memos.id', $id)
                ->whereNull('memos.deleted_at')
                ->select('memos.*', 'tags.id AS tag_id')
                ->get()
                ->toArray();
    }
}