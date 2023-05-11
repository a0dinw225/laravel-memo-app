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
     * get user memo with tag
     *
     * @param int $userId
     * @param int $memoId
     * @return array
     */
    public function getUserMemoWithTag(int $userId, int $memoId): array
    {
        return $this->memoTag::where([
            'user_id' => $userId,
            'memo_id' => $memoId,
        ])->get()->toArray();
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
     * delete memo tag batch
     *
     * @param array $userIds
     * @param array $memoIds
     * @param array $tagIds
     * @return void
     */
    public function deleteMemoTagBatch(array $userIds, array $memoIds, array $tagIds): void
    {
        $this->memoTag::whereIn('user_id', $userIds)
            ->whereIn('memo_id', $memoIds)
            ->whereIn('tag_id', $tagIds)
            ->update(['deleted_at' => now()]);
    }
}
