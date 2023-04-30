<?php

namespace App\Services;

use App\Repositories\MemoRepository;

class MemoService
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
   * Get user memos
   *
   * @param int $memoId
   * @param int $authId
   */
  public function getUserMemo(int $memoId, int $authId): array
  {
    $memo = $this->memoRepository->getUserMemoWithTagsById($memoId, $authId);

    return [
      'memo' => $memo,
    ];
  }

  /**
   * Get memo tags
   *
   * @param int $memoId
   * @param int $authId
   * @return array
   */
  public function getMemoTags(int $memoId, int $authId): array {
    $memoWithTagsData = $this->memoRepository->getUserMemoWithTagsById($memoId, $authId);
    $memoTagIds = [];

    foreach ($memoWithTagsData as $memo) {
      $memoTagIds[] = $memo['tag_id'];
    }

    return [
        'memo_with_tags' => $memoWithTagsData,
        'memo_tag_ids' => $memoTagIds,
    ];
  }
}
