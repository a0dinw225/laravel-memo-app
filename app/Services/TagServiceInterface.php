<?php

namespace App\Services;

interface TagServiceInterface
{
    /**
     * Get user tags
     *
     * @param int $authId
     * @return array
     */
    public function getUserTags(int $authId): array;

    /**
     * Check tag exists
     *
     * @param int $authId
     * @param string $tagName
     * @return bool
     */
    public function checkIfTagExists(int $authId, ?string $tagName): bool;

    /**
     * Attach tags to memo
     *
     * @param array|null $posts
     * @param int $memoId
     * @param bool $tagExists
     * @param int $authId
     * @return void
     */
    public function attachTagsToMemo(?array $posts, int $memoId, bool $tagExists, int $authId): void;
}
