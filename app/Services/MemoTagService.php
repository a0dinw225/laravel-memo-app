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
     * Delete memo tag
     *
     * @param int $memoId
     * @return void
     */
    public function deleteMemoTag(int $memoId): void
    {
        $this->memoTagRepository->deleteMemoTag($memoId);
    }
}
