<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\MemoTagRepository;
use App\Models\MemoTag;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\User;

class MemoTagRepositoryTest extends TestCase
{
    use RefreshDatabase;

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
        $this->memoTagRepository = new MemoTagRepository(new MemoTag());
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

        $memoTags = $this->memoTagRepository->getUserMemoWithTag($user->id, $memo->id);

        $this->assertCount(1, $memoTags);
        $this->assertSame($user->id, $memoTags[0]['user_id']);
        $this->assertSame($memo->id, $memoTags[0]['memo_id']);
        $this->assertSame($tag->id, $memoTags[0]['tag_id']);
    }

    /** @test */
    public function it_can_insert_memo_tag(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $this->memoTagRepository->insertMemoTag($user->id, $memo->id, $tag->id);

        $this->assertDatabaseHas('memo_tags', [
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
        ]);
    }

    /** @test */
    public function it_can_delete_memo_tag(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        MemoTag::factory()->create([
            'user_id' => $user->id,
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
        ]);

        $this->memoTagRepository->deleteMemoTag($memo->id);

        $this->assertDatabaseMissing('memo_tags', [
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
        ]);
    }
}
