<?php
namespace App\Services;

interface MemoTagServiceInterface
{
    /**
     * Get user memo with tag
     *
     * @param int $userId
     * @param int $memoId
     * @return array
     */
    public function getUserMemoWithTag(int $userId, int $memoId): array;

    /**
     * Delete memo tag
     *
     * @param array|null $memoWithTags
     * @return void
     */
    public function deleteMemoTag(?array $memoWithTags): void;
}
