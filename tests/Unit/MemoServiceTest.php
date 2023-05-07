<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\MemoRepository;
use App\Services\MemoService;
use App\Models\User;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;

class MemoServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var MemoService
     */
    protected $memoService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $memoRepository = new MemoRepository(new Memo());
        $this->memoService = new MemoService($memoRepository);
    }

    /** @test */
    public function it_can_get_user_memo(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);

        $result = $this->memoService->getUserMemo($memo->id, $user->id);

        $this->assertIsArray($result);
        $this->assertSame($memo->id, $result['memo'][0]['id']);
    }

    /** @test */
    public function it_can_get_memo_tags(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        MemoTag::factory()->create([
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
        ]);

        $result = $this->memoService->getMemoTags($memo->id, $user->id);

        $this->assertIsArray($result);
        $this->assertSame($tag->id, $result['memo_tag_ids'][0]);
    }

    /** @test */
    public function it_can_create_new_memo(): void
    {
        $user = User::factory()->create();
        $data = [
            'content' => 'Sample memo content',
        ];

        $memoId = $this->memoService->createNewMemoGetId($data, $user->id);

        $this->assertDatabaseHas('memos', [
            'id' => $memoId,
            'content' => $data['content'],
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_update_memo(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $updatedContent = 'Updated memo content';

        $this->memoService->updateMemo($memo->id, $updatedContent);

        $this->assertDatabaseHas('memos', [
            'id' => $memo->id,
            'content' => $updatedContent,
        ]);
    }

    /** @test */
    public function it_can_delete_memo(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);

        $this->memoService->deleteMemo($memo->id);

        $this->assertSoftDeleted('memos', [
            'id' => $memo->id,
        ]);
    }
}
