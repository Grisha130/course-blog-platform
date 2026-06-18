<?php
 
namespace Database\Factories;
 
use App\Enums\BlogStatus;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
 
/**
 * @extends Factory<Blog>
 */
class BlogFactory extends Factory
{
    protected $model = Blog::class;
 
    public function definition(): array
    {
        $title = fake()->unique()->sentence(fake()->numberBetween(4, 8));
        $title = rtrim($title, '.');
        $status = fake()->randomElement(BlogStatus::cases());
 
        return [
            'title'        => $title,
            'slug'         => Str::slug($title) . '-' . Str::random(5),
            'content'      => fake()->paragraphs(fake()->numberBetween(4, 8), true),
            'image'        => null,
            'status'       => $status,
            'is_active'    => true,
            'user_id'      => User::factory(),
            'category_id'  => Category::factory(),
            'published_at' => $status === BlogStatus::PUBLISHED ? fake()->dateTimeBetween('-6 months') : null,
        ];
    }
 
    public function published(): static
    {
        return $this->state(fn () => [
            'status'       => BlogStatus::PUBLISHED,
            'published_at' => fake()->dateTimeBetween('-6 months'),
        ]);
    }
 
    public function draft(): static
    {
        return $this->state(fn () => [
            'status'       => BlogStatus::DRAFT,
            'published_at' => null,
        ]);
    }
}
 