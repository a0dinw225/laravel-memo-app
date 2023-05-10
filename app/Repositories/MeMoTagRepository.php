<?php

namespace App\Repositories;

use App\Models\MemoTag;

class MemoTagRepository
{
    /** @var MemoTag */
    protected $memoTag;

    /**
     * MemoTagRepository constructor.
     *
     * @param MemoTag $memoTag
     */
    public function __construct(MemoTag $memoTag)
    {
        $this->memoTag = $memoTag;
    }

    /**
     * insert memo tag
     *
     * @param int $userId
     * @param int $memoId
     * @param int $tagId
     * @return void
     */
    public function insertMemoTag(int $userId, int $memoId, int $tagId): void
    {
        $this->memoTag::insert([
            'user_id' => $userId,
            'memo_id' => $memoId,
            'tag_id' => $tagId,
        ]);
    }

    /**
     * delete memo tag
     *
     * @param int $memoId
     * @return void
     */
    public function deleteMemoTag(int $memoId): void
    {
        $this->memoTag::where('memo_id', $memoId)->delete();
    }
}
