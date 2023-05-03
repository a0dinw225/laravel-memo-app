<?php

namespace App\Repositories;

Interface MemoTagRepositoryInterface
{
    /**
     * insert memo tag
     *
     * @param int $memoId
     * @param int $tagId
     * @return void
     */
    public function insertMemoTag(int $memoId, int $tagId): void;

    /**
     * delete memo tag
     *
     * @param int $memoId
     * @return void
     */
    public function deleteMemoTag(int $memoId): void;
}
