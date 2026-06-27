<?php
 
namespace Database\Factories;
 
use App\Models\Course;
use App\Models\CourseComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
 
/**
 * @extends Factory<CourseComment>
 */
class CourseCommentFactory extends Factory
{
    protected $model = CourseComment::class;
 
    public function definition(): array
    {
        return [
            'comment'   => fake()->sentence(),
            'user_id'   => User::inRandomOrder()->first()?->id ?? User::factory(),
            'course_id' => Course::inRandomOrder()->first()?->id ?? Course::factory(),
        ];
    }
}