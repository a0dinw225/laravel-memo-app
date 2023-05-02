<?php

namespace App\Services;

use App\Repositories\TagRepository;
use App\Repositories\MemoTagRepository;

class TagService {
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
     * @param int $authId
     * @return array
     */
    public function getUserTags(int $authId): array {
        $userTags = $this->tagRepository->getUserTags($authId);

        return [
            'user_tags' => $userTags,
        ];
    }

    /**
     * Check tag exists
     *
     * @param int $authId
     * @param string $tagName
     * @return bool
     */
    public function checkIfTagExists(int $authId, ?string $tagName): bool
    {
        $tagExists = $this->tagRepository->checkTagExists($authId, $tagName);
        return $tagExists;
    }

    /**
     * Attach tags to memo
     *
     * @param array|null $posts
     * @param int $memoId
     * @param bool $tagExists
     * @param int $authId
     * @return void
     */
    public function attachTagsToMemo(?array $posts, int $memoId, bool $tagExists, int $authId): void
    {
        if (!empty($posts['new_tag']) && !$tagExists) {
            $tagId = $this->tagRepository->insertTagGetId($posts['new_tag'], $authId);
            $this->memoTagRepository->insertMemoTag($memoId, $tagId);
        }

        if (!empty($posts['tags'][0])) {
            foreach ($posts['tags'] as $tag) {
                $this->memoTagRepository->insertMemoTag($memoId, $tag);
            }
        }
    }
}
