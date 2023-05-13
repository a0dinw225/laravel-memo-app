<?php

namespace App\Repositories;

use App\Models\Memo;

class MemoRepository
{
    /** @var Memo */
    protected $memo;

    /**
     * MemoRepository constructor.
     *
     * @param Memo $memo
     */
    public function __construct(Memo $memo)
    {
        $this->memo = $memo;
    }

    /**
     * get user memo
     *
     * @param int $userId
     * @param int $memoId
     * @return array
     */
    public function getUserMemoById(int $userId, int $memoId): array
    {
        return $this->memo::where('user_id', $userId)
                        ->where('id', $memoId)
                        ->whereNull('deleted_at')
                        ->first()
                        ->toArray();
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
                ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
                ->leftJoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
                ->where('memos.user_id', $userId)
                ->where('memos.id', $memoId)
                ->whereNull('memos.deleted_at')
                ->select('memos.*', 'tags.id AS tag_id')
                ->get()
                ->toArray();
    }

    /**
     * insert memo
     *
     * @param array $posts
     * @param int $userId
     * @return int
     */
    public function insertMemoGetId(array $posts, int $userId): int
    {
        return $this->memo::insertGetId([
            'content' => $posts['content'],
            'user_id' => $userId,
        ]);
    }

    /**
     * update memo
     *
     * @param int $memoId
     * @param string $memoContext
     * @return void
     */
    public function updateMemo(int $memoId, string $memoContext): void
    {
        $this->memo::where('id', $memoId)
                    ->update([
                        'content' => $memoContext,
                    ]);
    }

    /**
     * delete memo
     *
     * @param int $memoId
     * @return void
     */
    public function deleteMemo(int $memoId): void
    {
        $this->memo::where('id', $memoId)->update([
            'deleted_at' => date("Y-m-d H:i:s", time())
        ]);
    }
}
