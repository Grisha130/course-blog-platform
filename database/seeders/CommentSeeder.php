<?php
 
namespace Database\Seeders;
 
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\Course;
use App\Models\CourseComment;
use App\Models\User;
use Illuminate\Database\Seeder;
 
class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users   = User::all();
        $courses = Course::all();
        $blogs   = Blog::all();
 
        if ($users->isEmpty()) return;
 
        foreach ($courses as $course) {
            CourseComment::factory(rand(2, 4))->create([
                'course_id' => $course->id,
                'user_id'   => $users->random()->id,
            ]);
        }
 
        foreach ($blogs as $blog) {
            BlogComment::factory(rand(2, 5))->create([
                'blog_id' => $blog->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}