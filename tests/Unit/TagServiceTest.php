<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\TagRepository;
use App\Services\TagService;
use App\Models\Tag;
use App\Models\User;


class TagServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var TagService
     */
    protected $tagService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $tagRepository = new TagRepository(new Tag());
        $this->tagService = new TagService($tagRepository);
    }

    /** @test */
    public function it_can_get_tag_by_id(): void
    {
        $tag = Tag::factory()->create();

        $result = $this->tagService->getTagById($tag->id);

        $this->assertIsArray($result);
        $this->assertSame($tag->id, $result['id']);
    }

    /** @test */
    public function it_can_get_user_tags(): void
    {
        $user = User::factory()->create();
        Tag::factory()->count(3)->create(['user_id' => $user->id]);

        $result = $this->tagService->getUserTags($user->id);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
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
    public function it_can_delete_tag(): void
    {
        $tag = Tag::factory()->create();

        $this->tagService->deleteTag($tag->id);

        $this->assertSoftDeleted('tags', [
            'id' => $tag->id,
        ]);
    }
}
