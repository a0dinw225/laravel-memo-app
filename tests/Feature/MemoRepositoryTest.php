<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\MemoRepository;
use App\Models\Memo;
use App\Models\User;
use App\Models\Tag;
use App\Models\MemoTag;

class MemoRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var MemoRepository
     */
    protected $memoRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->memoRepository = new MemoRepository(new Memo());
    }

    /** @test */
    public function it_can_get_user_memo_with_tags_by_id(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $tag = Tag::factory()->create(['user_id' => $user->id]);
        $memoTag = MemoTag::factory()->create([
            'memo_id' => $memo->id,
            'tag_id' => $tag->id,
        ]);

        $result = $this->memoRepository->getUserMemoWithTagsById($memo->id, $user->id);

        $this->assertIsArray($result);
        $this->assertEquals($memo->id, $result[0]['id']);
        $this->assertEquals($tag->id, $result[0]['tag_id']);
    }

    /** @test */
    public function it_can_insert_memo_get_id(): void
    {
        $user = User::factory()->create();
        $data = [
            'content' => 'Test content',
        ];

        $memoId = $this->memoRepository->insertMemoGetId($data, $user->id);

        $this->assertDatabaseHas('memos', [
            'id' => $memoId,
            'user_id' => $user->id,
            'content' => 'Test content',
        ]);
    }

    /** @test */
    public function it_can_update_memo(): void
    {
        $memo = Memo::factory()->create();
        $newContent = 'Updated memo content';

        $this->memoRepository->updateMemo($memo->id, $newContent);

        $this->assertDatabaseHas('memos', [
            'id' => $memo->id,
            'content' => $newContent,
        ]);
    }

    /** @test */
    public function it_can_delete_memo(): void
    {
        $memo = Memo::factory()->create();

        $this->memoRepository->deleteMemo($memo->id);

        $this->assertSoftDeleted('memos', [
            'id' => $memo->id,
        ]);
    }
}
