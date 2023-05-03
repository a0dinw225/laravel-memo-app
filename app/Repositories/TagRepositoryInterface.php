<?php

namespace App\Repositories;

interface TagRepositoryInterface
{
    /**
     * Get user tags
     *
     * @param int $authId
     * @return array
     */
    public function getUserTags(int $authId): array;

    /**
     * get tag ids
     *
     * @param int $userId
     * @param array|null $tagNames
     * @return array
     */
    public function getTagIds(int $authId, ?array $tagNames): array;

    /**
     * insert tag and get id
     *
     * @param string $tagName
     * @param int $userId
     * @return int
     */
    public function insertTagGetId(string $tagName, int $authId): int;

    /**
     * check tag exists
     *
     * @param int $userId
     * @param string|null $tagName
     * @return bool
     */
    public function checkTagExists(int $authId, ?string $tagName): bool;
}
