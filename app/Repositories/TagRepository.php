<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository
{
  /** @var Tag */
  protected $tag;

  /**
   * TagRepository constructor.
   *
   * @param Tag $tag
   */
  public function __construct(Tag $tag)
  {
    $this->tag = $tag;
  }

  /**
   * Find user tags
   *
   * @param int $userId
   * @return array
   */
  public function getUserTags(int $userId): array
  {
    return $this->tag::where('user_id', $userId)
                      ->whereNull('deleted_at')
                      ->orderBy('id', 'DESC')
                      ->get()
                      ->toArray();
  }
}
