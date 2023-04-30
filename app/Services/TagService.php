<?php

namespace App\Services;

use App\Repositories\TagRepository;

class TagService {
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
     * @param int $authId
     * @return array
     */
    public function getUserTags(int $authId): array {
        $userTags = $this->tagRepository->getUserTags($authId);

        return [
            'user_tags' => $userTags,
        ];
    }
}
