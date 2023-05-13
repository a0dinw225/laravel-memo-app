<?php

namespace App\Services;

use App\Repositories\MemoRepository;

class MemoService implements MemoServiceInterface
{
    /** @var MemoRepository */
    protected $memoRepository;

    /**
     * MemoService constructor.
     *
     * @param MemoRepository $memoRepository
     */

    public function __construct(MemoRepository $memoRepository)
    {
        $this->memoRepository = $memoRepository;
    }

    /**
     * Get user memo by id
     *
     * @param int $userId
     * @param int $memoId
     * @return array
     */
    public function getUserMemoById(int $userId, int $memoId): array
    {
        return $this->memoRepository->getUserMemoById($userId, $memoId);
    }


    /**
     * Get user memos
     *
     * @param int $memoId
     * @param int $userId
     * @return array
     */
    public function getUserMemo(int $memoId, int $userId): array
    {
        $memo = $this->memoRepository->getUserMemoWithTagsById($memoId, $userId);

        return [
            'memo' => $memo,
        ];
    }

    /**
     * Get memo tags
     *
     * @param int $memoId
     * @param int $userId
     * @return array
     */
    public function getMemoTags(int $memoId, int $userId): array
    {
        $memoWithTagsData = $this->memoRepository->getUserMemoWithTagsById($memoId, $userId);
        $memoTagIds = [];

        foreach ($memoWithTagsData as $memo) {
            $memoTagIds[] = $memo['tag_id'];
        }

        return [
            'memo_with_tags' => $memoWithTagsData,
            'memo_tag_ids' => $memoTagIds,
        ];
    }

    /**
     * Create new memo
     *
     * @param array|null $posts
     * @param int $userId
     * @return int
     */
    public function createNewMemoGetId(?array $posts, int $userId): int
    {
        return $this->memoRepository->insertMemoGetId($posts, $userId);
    }

    /**
     * Update memo
     *
     * @param int $memoId
     * @param string $memoContext
     * @return void
     */
    public function updateMemo(int $memoId, string $memoContext): void
    {
        $this->memoRepository->updateMemo($memoId, $memoContext);
    }

    /**
     * Delete memo
     *
     * @param int $memoId
     * @return void
     */
    public function deleteMemo(int $memoId): void
    {
        $this->memoRepository->deleteMemo($memoId);
    }
}
