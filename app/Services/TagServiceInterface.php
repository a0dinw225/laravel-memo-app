<?php

namespace App\Services;

interface TagServiceInterface
{
    /**
     * get tag by id
     *
     * @param int $tagId
     * @return array
     */
    public function getTagById(int $tagId): array;

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
}
