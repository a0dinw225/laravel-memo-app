<?php

namespace App\Services;

interface TagServiceInterface
{
    /**
     * Get user tags
     *
     * @param int $userId
     * @return array
     */
    public function getUserTags(int $userId): array;

    /**
     * Check tag exists
     *
     * @param int $userId
     * @param string $tagName
     * @return bool
     */
    public function checkIfTagExists(int $userId, ?string $tagName): bool;

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
}
