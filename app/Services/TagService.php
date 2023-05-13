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
     * Get user tags
     *
     * @param int $userId
     * @return array
     */
    public function getUserTags(int $userId): array
    {
        $userTags = $this->tagRepository->getUserTags($userId);

        return [
            'user_tags' => $userTags,
        ];
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
}
