<?php

namespace App\Repositories;

interface MemoRepositoryInterface
{
    /**
     * get user memo
     *
     * @param int $userId
     * @param int $memoId
     * @return array
     */
    public function getUserMemoById(int $userId, int $memoId): array;

    /**
     * get tags with memo id
     *
     * @param int $memoId
     * @param int $userId
     * @return array
     */
    public function getUserMemoWithTagsById(int $memoId, int $userId): array;

    /**
     * insert memo
     *
     * @param array $posts
     * @param int $userId
     * @return int
     */
    public function insertMemoGetId(array $posts, int $userId): int;

    /**
     * update memo
     *
     * @param int $memoId
     * @param string $memoContext
     * @return void
     */
    public function updateMemo(int $memoId, string $memoContext): void;

    /**
     * delete memo
     *
     * @param int $memoId
     * @return void
     */
    public function deleteMemo(int $memoId): void;
}
