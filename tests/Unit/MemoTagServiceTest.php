<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\MemoTagRepository;
use App\Repositories\TagRepository;
use App\Services\MemoTagService;
use App\Models\MemoTag;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\User;

class MemoTagServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var MemoTagService
     */
    protected $memoTagService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $memoTagRepository = new MemoTagRepository(new MemoTag());
        $tagRepository = new TagRepository(new Tag());
        $this->memoTagService = new MemoTagService($memoTagRepository, $tagRepository);
    }

    /** @test */
    public function it_can_get_user_memo_with_tag(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        MemoTag::factory()->create([
            'user_id' => $user->id,
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
        ]);

        $memoTags = $this->memoTagService->getUserMemoWithTag($user->id, $memo->id);

        $this->assertCount(1, $memoTags);
        $this->assertSame($user->id, $memoTags[0]['user_id']);
        $this->assertSame($memo->id, $memoTags[0]['memo_id']);
        $this->assertSame($tag->id, $memoTags[0]['tag_id']);
    }

    /** @test */
    public function it_can_get_tag_ids_for_user_memo(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $tags = Tag::factory(2)->create(['user_id' => $user->id]);

        foreach ($tags as $tag) {
            MemoTag::factory()->create([
                'user_id' => $user->id,
                'memo_id' => $memo->id,
                'tag_id' => $tag->id,
            ]);
        }

        $tagIds = $this->memoTagService->getTagIdsForUserMemo($user->id, $memo->id);

        $this->assertCount(2, $tagIds);
        foreach ($tags as $tag) {
            $this->assertContains($tag->id, $tagIds);
        }
    }

    /** @test */
    public function it_can_new_tag_attach_tags_to_memo(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $tag = Tag::factory()->create([
            'name' => 'Test Old Tag',
            'user_id' => $user->id
        ]);

        MemoTag::factory()->create([
            'user_id' => $user->id,
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
        ]);

        $post = [
            'content' => $memo->name,
            'new_tag' => 'Test New Tag',
        ];

        $this->memoTagService->attachTagsToMemo($post, $memo->id, false, $user->id);

        $this->assertDatabaseHas('memo_tags', [
            'user_id' => $user->id,
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_can_old_tag_attach_tags_to_memo(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $tag = Tag::factory()->create([
            'name' => 'Test Old Tag',
            'user_id' => $user->id
        ]);

        MemoTag::factory()->create([
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
            'user_id' => $user->id,
        ]);

        $post = [
            'content' => $memo->name,
            'new_tag' => null,
            'tags' => ["$tag->id"]
        ];

        $this->memoTagService->attachTagsToMemo($post, $memo->id, true, $user->id);

        $this->assertDatabaseHas('memo_tags', [
            'user_id' => $user->id,
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_can_delete_memo_tag(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $tags = Tag::factory(3)->create(['user_id' => $user->id]);
        foreach ($tags as $tag) {
            MemoTag::factory()->create([
                'user_id' => $user->id,
                'memo_id' => $memo->id,
                'tag_id' => $tag->id,
            ]);
        }

        $memoTags = $this->memoTagService->getUserMemoWithTag($user->id, $memo->id);
        $this->assertCount(3, $memoTags);

        $this->memoTagService->deleteMemoTag($memoTags);

        foreach ($memoTags as $memoTag) {
            $this->assertDatabaseHas('memo_tags', [
                'user_id' => $memoTag['user_id'],
                'memo_id' => $memoTag['memo_id'],
                'tag_id' => $memoTag['tag_id'],
                'deleted_at' => now(),
            ]);
        }
    }

    /** @test */
    public function it_can_delete_memo_tag_when_memo_with_tag_is_null(): void
    {
        $this->memoTagService->deleteMemoTag(null);
        $this->assertTrue(true);
    }
}
