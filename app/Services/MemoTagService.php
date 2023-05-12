<?php

namespace App\Services;

use App\Repositories\MemoTagRepository;

class MemoTagService implements MemoTagServiceInterface
{
    /** @var MemoTagRepository */
    protected $memoTagRepository;

    /**
     * MemoTagService constructor.
     *
     * @param MemoTagRepository $memoTagRepository
     */
    public function __construct(MemoTagRepository $memoTagRepository)
    {
        $this->memoTagRepository = $memoTagRepository;
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
