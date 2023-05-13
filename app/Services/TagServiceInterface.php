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
}
