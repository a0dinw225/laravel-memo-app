<?php

namespace App\Repositories;

use App\Models\Memo;

class MemoRepository
{
    /** @var Memo */
    protected $memo;

    /**
     * MemoRepository constructor.
     * @param Memo $memo
     */
    public function __construct(Memo $memo)
    {
        $this->memo = $memo;
    }

    /**
     * get tags with memo id
     *
     * @param int $memoId
     * @param int $userId
     * @return array
     */
    public function getUserMemoWithTagsById(int $memoId, int $userId): array
    {
        return $this->memo
                ->join('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
                ->join('tags', 'memo_tags.tag_id', '=', 'tags.id')
                ->where('memos.user_id', $userId)
                ->where('memos.id', $memoId)
                ->whereNull('memos.deleted_at')
                ->select('memos.*', 'tags.id AS tag_id')
                ->get()
                ->toArray();
    }

    /**
     * insert user memos
     *
     * @param array $posts
     * @param int $userId
     * @return array
     */
    public function insertMemoGetId(array $posts, int $authId): int
    {
        return $this->memo::insertGetId([
            'content' => $posts['content'],
            'user_id' => $authId,
        ]);
    }
}