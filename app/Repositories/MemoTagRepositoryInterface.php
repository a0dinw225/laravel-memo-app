<?php

namespace App\Repositories;

Interface MemoTagRepositoryInterface
{
    /**
     * Get user memos
     *
     * @param int $userId
     * @param int $memoId
     * @return array
     */
    public function getUserMemoWithTag(int $userId, int $memoId): array;

    /**
     * insert memo tag
     *
     * @param int $userId
     * @param int $memoId
     * @param int $tagId
     * @return void
     */
    public function insertMemoTag(int $userId, int $memoId, int $tagId): void;

    /**
     * delete memo tag batch
     *
     * @param array $userIds
     * @param array $memoIds
     * @param array $tagIds
     * @return void
     */
    public function deleteMemoTagBatch(array $userIds, array $memoIds, array $tagIds): void;
}
