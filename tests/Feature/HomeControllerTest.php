<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\HomeController;
use App\Services\MemoServiceInterface;
use App\Services\MemoTagServiceInterface;
use App\Services\TagServiceInterface;
use App\Models\User;
use App\Models\Tag;
use App\Models\Memo;
use App\Models\MemoTag;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @var TagServiceInterface */
    private $tagService;

    /** @var MemoServiceInterface */
    private $memoService;

    /** @var MemoTagServiceInterface */
    private $memoTagService;

    /** @var HomeController */
    private $homeController;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tagService = $this->getMockBuilder(TagServiceInterface::class)->getMock();
        $this->memoService = $this->getMockBuilder(MemoServiceInterface::class)->getMock();
        $this->memoTagService = $this->getMockBuilder(MemoTagServiceInterface::class)->getMock();

        $this->app->instance(TagServiceInterface::class, $this->tagService);
        $this->app->instance(MemoServiceInterface::class, $this->memoService);
        $this->app->instance(MemoTagServiceInterface::class, $this->memoTagService);
    }

    /** @test */
    public function it_can_show_the_application_dashboard_with_no_user()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function it_can_show_the_application_dashboard()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tags = Tag::factory()->count(3)->create(['user_id' => $user->id]);

        $this->tagService->expects($this->once())
            ->method('getUserTags')
            ->with($user->id)
            ->willReturn($tags->toArray());

        Memo::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->get(route('home'));

        $response->assertStatus(200)
            ->assertViewIs('create')
            ->assertViewHasAll(['tags']);
    }

    /** @test */
    public function it_can_show_the_application_dashboard_with_no_tags()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->tagService->expects($this->once())
            ->method('getUserTags')
            ->with($user->id)
            ->willReturn([]);

        Memo::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->get(route('home'));

        $response->assertStatus(200)
            ->assertViewIs('create')
            ->assertViewHasAll(['tags']);
    }

    /** @test */
    public function it_can_create_a_new_memo()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $memoContent = $this->faker->text;
        $newTag = $this->faker->word;
        $tagIds = [1, 2, 3];

        $this->memoService->expects($this->once())
            ->method('createNewMemoGetId')
            ->with([
                'content' => $memoContent,
                'new_tag' => $newTag,
                'tags' => $tagIds
            ], $user->id)
            ->willReturn(1);

            $this->tagService->expects($this->once())
            ->method('checkIfTagExists')
            ->with($user->id, $newTag)
            ->willReturn(false);

        $this->memoTagService->expects($this->once())
            ->method('attachTagsToMemo')
            ->with([
                'content' => $memoContent,
                'new_tag' => $newTag,
                'tags' => $tagIds
            ], 1, false, $user->id);

        $response = $this->post(route('store'), [
            'content' => $memoContent,
            'new_tag' => $newTag,
            'tags' => $tagIds,
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function it_can_create_a_new_memo_with_no_tags()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $memoContent = $this->faker->text;
        $newTag = $this->faker->word;
        $tagIds = [];

        $this->memoService->expects($this->once())
            ->method('createNewMemoGetId')
            ->with([
                'content' => $memoContent,
                'new_tag' => $newTag,
                'tags' => $tagIds
            ], $user->id)
            ->willReturn(1);

        $this->tagService->expects($this->once())
            ->method('checkIfTagExists')
            ->with($user->id, $newTag)
            ->willReturn(false);

        $this->memoTagService->expects($this->once())
            ->method('attachTagsToMemo')
            ->with([
                'content' => $memoContent,
                'new_tag' => $newTag,
                'tags' => $tagIds
            ], 1, false, $user->id);

        $response = $this->post(route('store'), [
            'content' => $memoContent,
            'new_tag' => $newTag,
            'tags' => $tagIds,
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function it_can_create_a_new_memo_with_no_new_tag()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $memoContent = $this->faker->text;
        $newTag = '';
        $tagIds = [1, 2, 3];

        $this->memoService->expects($this->once())
            ->method('createNewMemoGetId')
            ->with([
                'content' => $memoContent,
                'new_tag' => $newTag,
                'tags' => $tagIds
            ], $user->id)
            ->willReturn(1);

        $this->tagService->expects($this->once())
            ->method('checkIfTagExists');

        $this->memoTagService->expects($this->once())
            ->method('attachTagsToMemo')
            ->with([
                'content' => $memoContent,
                'new_tag' => $newTag,
                'tags' => $tagIds
            ], 1, false, $user->id);

        $response = $this->post(route('store'), [
            'content' => $memoContent,
            'new_tag' => $newTag,
            'tags' => $tagIds,
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function it_can_create_a_new_memo_with_no_new_tag_and_no_tags()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $memoContent = $this->faker->text;
        $newTag = '';
        $tagIds = [];

        $this->memoService->expects($this->once())
            ->method('createNewMemoGetId')
            ->with([
                'content' => $memoContent,
                'new_tag' => $newTag,
                'tags' => $tagIds
            ], $user->id)
            ->willReturn(1);

        $this->tagService->expects($this->once())
            ->method('checkIfTagExists');

        $this->memoTagService->expects($this->once())
            ->method('attachTagsToMemo')
            ->with([
                'content' => $memoContent,
                'new_tag' => $newTag,
                'tags' => $tagIds
            ], 1, false, $user->id);

        $response = $this->post(route('store'), [
            'content' => $memoContent,
            'new_tag' => $newTag,
            'tags' => $tagIds,
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function it_can_edit_a_memo()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $memoTagIds = MemoTag::factory()->count(3)->create([
            'user_id' => $user->id,
            'memo_id' => $memo->id,
        ])->pluck('tag_id')->toArray();
        $tags = Tag::factory()->count(3)->create(['user_id' => $user->id]);

        $this->memoService->expects($this->once())
            ->method('getUserMemoById')
            ->with($user->id, $memo->id)
            ->willReturn($memo->toArray());

        $this->memoTagService->expects($this->once())
            ->method('getTagIdsForUserMemo')
            ->with($user->id, $memo->id)
            ->willReturn($memoTagIds);

        $this->tagService->expects($this->once())
            ->method('getUserTags')
            ->with($user->id)
            ->willReturn($tags->toArray());

        $response = $this->get(route('edit', ['id' => $memo->id]));

        $response->assertStatus(200)
            ->assertViewIs('edit')
            ->assertViewHasAll(['memo', 'memoTagIds', 'tags']);
    }

    /** @test */
    public function it_can_update_a_memo_with_new_tag_and_tags()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $memoId = $memo->id;
        $memoContent = $this->faker->text;
        $newTag = $this->faker->word;
        $tagIds = [1, 2, 3];

        $postData = [
            'memo_id' => $memoId,
            'content' => $memoContent,
            'new_tag' => $newTag,
            'tags' => $tagIds,
        ];

        $this->memoService->expects($this->once())
            ->method('updateMemo')
            ->with($memoId, $memoContent);

        $this->memoTagService->expects($this->once())
            ->method('getUserMemoWithTag')
            ->with($user->id, $memoId)
            ->willReturn([]);

        $this->memoTagService->expects($this->once())
            ->method('deleteMemoTag');

        $this->tagService->expects($this->once())
            ->method('checkIfTagExists')
            ->with($user->id, $newTag)
            ->willReturn(true);

        $this->memoTagService->expects($this->once())
            ->method('attachTagsToMemo')
            ->with($postData, $memoId, true, $user->id);

        $response = $this->post(route('update'), $postData);

        $response->assertStatus(302)
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function it_can_update_a_memo_with_new_tag_and_no_tags()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $memoId = $memo->id;
        $memoContent = $this->faker->text;
        $newTag = $this->faker->word;
        $tagIds = [];

        $postData = [
            'memo_id' => $memoId,
            'content' => $memoContent,
            'new_tag' => $newTag,
            'tags' => $tagIds,
        ];

        $this->memoService->expects($this->once())
            ->method('updateMemo')
            ->with($memoId, $memoContent);

        $this->memoTagService->expects($this->once())
            ->method('getUserMemoWithTag')
            ->with($user->id, $memoId)
            ->willReturn([]);

        $this->memoTagService->expects($this->once())
            ->method('deleteMemoTag');

        $this->tagService->expects($this->once())
            ->method('checkIfTagExists')
            ->with($user->id, $newTag)
            ->willReturn(true);

        $this->memoTagService->expects($this->once())
            ->method('attachTagsToMemo')
            ->with($postData, $memoId, true, $user->id);

        $response = $this->post(route('update'), $postData);

        $response->assertStatus(302)
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function it_can_delete_a_memo()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $memo = Memo::factory()->create(['user_id' => $user->id]);
        $memoId = $memo->id;

        $postData = [
            'memo_id' => $memoId,
        ];

        $this->memoTagService->expects($this->once())
            ->method('getUserMemoWithTag')
            ->with($user->id, $memoId)
            ->willReturn([]);

        $this->memoTagService->expects($this->once())
            ->method('deleteMemoTag');

        $this->memoService->expects($this->once())
            ->method('deleteMemo')
            ->with($memoId);

        $response = $this->post(route('delete'), $postData);

        $response->assertStatus(302)
            ->assertRedirect(route('home'));
    }

}
