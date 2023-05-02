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

    /**
   * get tag ids
   *
   * @param int $userId
   * @param array|null $tagNames
   * @return array
   */
    public function getTagIds(int $authId, ?array $tagNames): array
    {
        return $this->tag::where('user_id', $authId)
                        ->whereIn('name', $tagNames)
                        ->whereNull('deleted_at')
                        ->pluck('id')
                        ->toArray();
    }

   /**
    * insert tag and get id
    *
    * @param string $tagName
    * @param int $userId
    * @return int
    */
    public function insertTagGetId(string $tagName, int $authId): int
    {
        return $this->tag->insertGetId([
            'name' => $tagName,
            'user_id' => $authId,
        ]);
    }

    /**
     * check tag exists
     * @param int $userId
     * @param string|null $tagName
     * @return bool
     */
    public function checkTagExists(int $userId, ?string $tagName): bool
    {
        return Tag::where('user_id', '=', $userId)->where('name', '=', $tagName)
        ->exists();
    }
}
