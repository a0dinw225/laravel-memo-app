<?php

namespace App\Services;

interface MemoServiceInterface
{
    /**
     * Get user memos
     *
     * @param int $memoId
     * @param int $authId
     * @return array
     */
    public function getUserMemo(int $memoId, int $authId): array;

    /**
     * Get memo tags
     *
     * @param int $memoId
     * @param int $authId
     * @return array
     */
    public function getMemoTags(int $memoId, int $authId): array;

    /**
     * Create new memo
     *
     * @param array|null $posts
     * @param int $authId
     * @return int
     */
    public function createNewMemoGetId(?array $posts, int $authId): int;

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
