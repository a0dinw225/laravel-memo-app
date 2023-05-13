<?php

namespace App\Services;

interface MemoServiceInterface
{
    /**
     * Get user memo by id
     *
     * @param int $userId
     * @param int $memoId
     * @return array
     */
    public function getUserMemoById(int $userId, int $memoId): array;

    /**
     * Get user memos
     *
     * @param int $memoId
     * @param int $userId
     * @return array
     */
    public function getUserMemo(int $memoId, int $userId): array;

    /**
     * Get memo tags
     *
     * @param int $memoId
     * @param int $userId
     * @return array
     */
    public function getMemoTags(int $memoId, int $userId): array;

    /**
     * Create new memo
     *
     * @param array|null $posts
     * @param int $userId
     * @return int
     */
    public function createNewMemoGetId(?array $posts, int $userId): int;

    /**
     * Update memo
     *
     * @param int $memoId
     * @param string $memoContext
     * @return void
     */
    public function updateMemo(int $memoId, string $memoContext): void;

    /**
     * Delete memo
     *
     * @param int $memoId
     * @return void
     */
    public function deleteMemo(int $memoId): void;
}
