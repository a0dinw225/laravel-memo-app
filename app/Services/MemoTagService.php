<?php

namespace App\Services;

use App\Repositories\MemoTagRepository;
use App\Repositories\TagRepository;

class MemoTagService implements MemoTagServiceInterface
{
    /** @var MemoTagRepository */
    protected $memoTagRepository;

    /** @var TagRepository */
    protected $tagRepository;

    /**
     * MemoTagService constructor.
     *
     * @param MemoTagRepository $memoTagRepository
     */
    public function __construct(MemoTagRepository $memoTagRepository, TagRepository $tagRepository)
    {
        $this->memoTagRepository = $memoTagRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * Get user memo with tag
     *
     * @param int $userId
     * @param int $memoId
     * @return array
     */
    public function getUserMemoWithTag(int $userId, int $memoId): array
    {
        return $this->memoTagRepository->getUserMemoWithTag($userId, $memoId);
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

    /**
     * Delete memo tag
     *
     * @param array|null $memoWithTags
     * @return void
     */
    public function deleteMemoTag(?array $memoWithTags): void
    {
        if (is_null($memoWithTags)) {
            return;
        }

        $batchSize = 100;

        foreach (array_chunk($memoWithTags, $batchSize) as $memoWithTagBatch) {
            $userIds = [];
            $memoIds = [];
            $tagIds = [];

            foreach ($memoWithTagBatch as $memoWithTag) {
                $userIds[] = $memoWithTag['user_id'];
                $memoIds[] = $memoWithTag['memo_id'];
                $tagIds[] = $memoWithTag['tag_id'];
            }

            $this->memoTagRepository->deleteMemoTagBatch($userIds, $memoIds, $tagIds);
        }
    }
}
