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
    public function it_can_show_the_application_dashboard()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tags = Tag::factory()->count(3)->create(['user_id' => $user->id]);

        $this->tagService->expects($this->once())
            ->method('getUserTags')
            ->with($user->id)
            ->willReturn(['user_tags' => $tags->toArray()]);

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
            ->willReturn(['user_tags' => []]);

        $response = $this->get(route('home'));

        $response->assertStatus(200)
            ->assertViewIs('create')
            ->assertViewHasAll(['tags']);
    }
}
