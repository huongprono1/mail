<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6, true);
        $slug = Str::slug($title).'-'.$this->faker->unique()->randomNumber(5);

        return [
            'title' => $title,
            'slug' => $slug,
            'content' => $this->faker->paragraphs(5, true),
            'cover_image' => $this->faker->optional()->imageUrl(800, 400, 'nature', true, 'cover'),
            'user_id' => User::inRandomOrder()->first()->id,
            'meta_title' => $this->faker->optional()->sentence(6),
            'meta_description' => $this->faker->optional()->text(160),
            'meta_keywords' => $this->faker->optional()->words(5, true),
            'is_published' => $this->faker->boolean(70),
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
