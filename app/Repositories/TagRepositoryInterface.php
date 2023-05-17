<?php

namespace App\Repositories;

interface TagRepositoryInterface
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
     * get tag ids
     *
     * @param int $userId
     * @param array|null $tagNames
     * @return array
     */
    public function getTagIds(int $userId, ?array $tagNames): array;

    /**
     * insert tag and get id
     *
     * @param string $tagName
     * @param int $userId
     * @return int
     */
    public function insertTagGetId(string $tagName, int $userId): int;

    /**
     * check tag exists
     *
     * @param int $userId
     * @param string|null $tagName
     * @return bool
     */
    public function checkTagExists(int $userId, ?string $tagName): bool;

    /**
     * delete tag
     *
     * @param int $tagId
     * @return void
     */
    public function deleteTag(int $tagId): void;
}
