<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
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
     * get tag by id
     *
     * @param int $tagId
     * @return array
     */
    public function getTagById(int $tagId): array
    {
        return $this->tag::where('id', $tagId)
                        ->whereNull('deleted_at')
                        ->first()
                        ->toArray();
    }

    /**
     * Get user tags
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
    public function getTagIds(int $userId, ?array $tagNames): array
    {
        return $this->tag::where('user_id', $userId)
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
    public function insertTagGetId(string $tagName, int $userId): int
    {
        return $this->tag->insertGetId([
            'name' => $tagName,
            'user_id' => $userId,
        ]);
    }

    /**
     * check tag exists
     *
     * @param int $userId
     * @param string|null $tagName
     * @return bool
     */
    public function checkTagExists(int $userId, ?string $tagName): bool
    {
        return Tag::where('user_id', '=', $userId)->where('name', '=', $tagName)
        ->exists();
    }

    /**
     * delete tag
     *
     * @param int $tagId
     * @return void
     */
    public function deleteTag(int $tagId): void
    {
        Tag::where('id', $tagId)->update([
            'deleted_at' => now(),
        ]);
    }
}
