<?php

namespace App\Services;

use App\Repositories\TagRepository;
use App\Repositories\MemoTagRepository;

class TagService implements TagServiceInterface
{
    /** @var TagRepository */
    protected $tagRepository;

    /** @var MemoTagRepository */
    protected $memoTagRepository;

    /**
     * TagService constructor.
     *
     * @param TagRepository $tagRepository
     * @param MemoTagRepository $memoTagRepository
     */
    public function __construct(TagRepository $tagRepository, MemoTagRepository $memoTagRepository) {
        $this->tagRepository = $tagRepository;
        $this->memoTagRepository = $memoTagRepository;
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

    /**
     * Attach tags to memo
     *
     * @param array|null $posts
     * @param int $memoId
     * @param bool $tagExists
     * @param int $userId
     * @return void
     */
    public function attachTagsToMemo(?array $posts, int $memoId, bool $tagExists, int $userId): void
    {
        if (!empty($posts['new_tag']) && !$tagExists) {
            $tagId = $this->tagRepository->insertTagGetId($posts['new_tag'], $userId);
            $this->memoTagRepository->insertMemoTag($userId, $memoId, $tagId);
        }

        if (!empty($posts['tags'][0])) {
            foreach ($posts['tags'] as $tag) {
                $this->memoTagRepository->insertMemoTag($userId, $memoId, $tag);
            }
        }
    }
}
