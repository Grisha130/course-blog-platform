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
            'comment' => fake()->sentences(fake()->numberBetween(1, 3), true),
            'user_id' => User::factory(),
            'blog_id' => Blog::factory(),
        ];
    }
}