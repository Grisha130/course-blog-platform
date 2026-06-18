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
            'comment'   => fake()->sentences(fake()->numberBetween(1, 3), true),
            'user_id'   => User::factory(),
            'course_id' => Course::factory(),
        ];
    }
}