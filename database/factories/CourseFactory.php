<?php
 
namespace Database\Factories;
 
use App\Enums\CourseStatus;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
 
/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;
 
    public function definition(): array
    {
        $title = fake()->unique()->sentence(fake()->numberBetween(3, 6));
        $title = rtrim($title, '.');
        $status = fake()->randomElement(CourseStatus::cases());
 
        return [
            'title'        => $title,
            'slug'         => Str::slug($title) . '-' . Str::random(5),
            'description'  => fake()->paragraphs(3, true),
            'image'        => 'courses/default-course.png', 
            'price'        => fake()->randomFloat(2, 0, 500),
            'status'       => $status,
            'is_active'    => true,
            'user_id'      => User::whereHas('roles', fn($q) => $q->where('name', 'Editor'))->inRandomOrder()->first()?->id ?? User::factory(),
            'published_at' => $status === CourseStatus::PUBLISHED ? fake()->dateTimeBetween('-6 months') : null,
        ];
    }
 
    public function published(): static
    {
        return $this->state(fn () => [
            'status'       => CourseStatus::PUBLISHED,
            'published_at' => fake()->dateTimeBetween('-6 months'),
        ]);
    }
 
    public function draft(): static
    {
        return $this->state(fn () => [
            'status'       => CourseStatus::DRAFT,
            'published_at' => null,
        ]);
    }
 
    public function free(): static
    {
        return $this->state(fn () => [
            'price' => 0,
        ]);
    }
}