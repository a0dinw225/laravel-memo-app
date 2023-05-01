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
   * @return array
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
  
  /**
   * Create new memo
   *
   * @param array|null $posts
   * @param int $authId
   * @return int
   */
  public function createNewMemoGetId(?array $posts, int $authId): int
  {
    return $this->memoRepository->insertMemoGetId($posts, $authId);
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
}
