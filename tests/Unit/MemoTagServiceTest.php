<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\MemoTagRepository;
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
        $memoTagService = new MemoTagRepository(new MemoTag());
        $this->memoTagService = new MemoTagService($memoTagService);
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

        $this->memoTagService->deleteMemoTag($memo->id);

        $this->assertDatabaseMissing('memo_tags', [
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
        ]);
    }

}
