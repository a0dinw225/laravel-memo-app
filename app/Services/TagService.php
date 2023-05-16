<?php

namespace App\Services;

use App\Repositories\TagRepository;

class TagService implements TagServiceInterface
{
    /** @var TagRepository */
    protected $tagRepository;

    /**
     * TagService constructor.
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository) {
        $this->tagRepository = $tagRepository;
    }

    /**
     * get tag by id
     *
     * @param int $tagId
     * @return array
     */
    public function getTagById(int $tagId): array
    {
        return $this->tagRepository->getTagById($tagId);
    }

    /**
     * Get user tags
     *
     * @param int $userId
     * @return array
     */
    public function getUserTags(int $userId): array
    {
        return $this->tagRepository->getUserTags($userId);
    }

    /**
     * Check tag exists
     *
     * @param int $userId
     * @param string $tagName
     * @return bool
     */
    public function checkIfTagExists(int $userId, ?string $tagName): bool
    {
        $tagExists = $this->tagRepository->checkTagExists($userId, $tagName);
        return $tagExists;
    }

    /**
     * Delete tag
     *
     * @param int $tagId
     * @return void
     */
    public function deleteTag(int $tagId): void
    {
        $this->tagRepository->deleteTag($tagId);
    }
}
