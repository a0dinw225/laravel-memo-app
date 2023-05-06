<?php

namespace Database\Factories;

use App\Models\MemoTag;
use App\Models\Memo;
use App\Models\Tag;
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
            'memo_id' => Memo::factory(),
            'tag_id' => Tag::factory(),
        ];
    }
}
