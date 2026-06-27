<?php
 
namespace Database\Factories;
 
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
 
/**
 * @extends Factory<BlogComment>
 */
class BlogCommentFactory extends Factory
{
    protected $model = BlogComment::class;
 
    public function definition(): array
    {
        return [
            'comment' => fake()->sentence(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'blog_id' => Blog::inRandomOrder()->first()?->id ?? Blog::factory(),
        ];
    }
}