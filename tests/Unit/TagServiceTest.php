<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\TagRepository;
use App\Repositories\MemoTagRepository;
use App\Services\TagService;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
use App\Models\User;


class TagServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var TagService
     */
    protected $tagService;

    /**
     * @var MemoTagRepository
     */
    protected $memoTagRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $tagRepository = new TagRepository(new Tag());
        $memoTagRepository = new MemoTagRepository(new MemoTag());
        $this->tagService = new TagService($tagRepository, $memoTagRepository);
    }

    /** @test */
    public function it_can_get_user_tags(): void
    {
        $user = User::factory()->create();
        Tag::factory()->count(3)->create(['user_id' => $user->id]);

        $result = $this->tagService->getUserTags($user->id);

        $this->assertIsArray($result);
        $this->assertCount(3, $result['user_tags']);
    }

    /** @test */
    public function it_can_check_if_tag_exists(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $result = $this->tagService->checkIfTagExists($user->id, $tag->name);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_check_if_not_tag_exists(): void
    {
        $user = User::factory()->create();
        Tag::factory()->create(['user_id' => $user->id]);

        $result = $this->tagService->checkIfTagExists($user->id, null);

        $this->assertFalse($result);
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

        $this->tagService->attachTagsToMemo($post, $memo->id, false, $user->id);

        $this->assertDatabaseHas('memo_tags', [
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
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

        $this->tagService->attachTagsToMemo($post, $memo->id, true, $user->id);

        $this->assertDatabaseHas('memo_tags', [
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
        ]);
    }
}
