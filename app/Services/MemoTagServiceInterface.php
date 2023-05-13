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
     * Get tag ids for user memo
     *
     * @param int $userId
     * @param int $memoId
     * @return array
     */
    public function getTagIdsForUserMemo(int $userId, int $memoId): array;

    /**
     * Attach tags to memo
     *
     * @param array|null $posts
     * @param int $memoId
     * @param bool $tagExists
     * @param int $userId
     * @return void
     */
    public function attachTagsToMemo(?array $posts, int $memoId, bool $tagExists, int $userId): void;

    /**
     * Delete memo tag
     *
     * @param array|null $memoWithTags
     * @return void
     */
    public function deleteMemoTag(?array $memoWithTags): void;
}
