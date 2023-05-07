<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\TagRepository;
use App\Models\Tag;
use App\Models\User;

class TagRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var TagRepository
     */
    protected $tagRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->tagRepository = new TagRepository(new Tag());
    }

    /** @test */
    public function it_can_get_user_tags(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $result = $this->tagRepository->getUserTags($tag->user_id);

        $this->assertIsArray($result);
        $this->assertSame($tag->id, $result[0]['id']);
    }

    /** @test */
    public function it_does_not_get_deleted_user_tags(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $deletedTag = Tag::factory()->create([
            'user_id' => $user->id,
            'deleted_at' => now(),
        ]);

        $result = $this->tagRepository->getUserTags($user->id);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    
        $tagIds = array_column($result, 'id');
    
        $this->assertContains($tag->id, $tagIds);
        $this->assertNotContains($deletedTag->id, $tagIds);
    }


    /** @test */
    public function it_can_get_tag_ids(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $result = $this->tagRepository->getTagIds($tag->user_id, [$tag->name]);

        $this->assertIsArray($result);
        $this->assertSame($tag->id, $result[0]);
    }

    /** @test */
    public function it_can_insert_tag_get_id(): void
    {

        $user = User::factory()->create();
        $data = [
            'name' => 'Test tag',
        ];

        $tagId = $this->tagRepository->insertTagGetId($data['name'], $user->id);

        $this->assertDatabaseHas('tags', [
            'id' => $tagId,
            'name' => $data['name'],
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_check_tag_exists(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $result = $this->tagRepository->checkTagExists($user->id, $tag->name);
        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_check_tag_not_exists(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $result = $this->tagRepository->checkTagExists($user->id, null);
        $this->assertFalse($result);
    }
}
