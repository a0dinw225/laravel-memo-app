<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tag;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_a_tag()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user, 'api');

        $response = $this->deleteJson(route('api.v1.tag.delete', ['id' => $tag->id]));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'タグを削除しました',
            ]);

        $deleted_at = Carbon::parse(Tag::find($tag->id)->deleted_at)->format('Y-m-d H:i:s');

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'deleted_at' => $deleted_at,
        ]);
    }

}

