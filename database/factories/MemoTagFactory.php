<?php

namespace Database\Factories;

use App\Models\MemoTag;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemoTagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MemoTag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'memo_id' => Memo::factory(),
            'tag_id' => Tag::factory(),
        ];
    }
}
