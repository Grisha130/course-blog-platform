<?php
 
namespace Database\Seeders;
 
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
 
class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $authors = User::whereHas('roles', fn ($q) =>
            $q->whereIn('name', ['Admin', 'Editor', 'Super Admin'])
        )->get();
 
        if ($authors->isEmpty()) {
            $authors = User::all();
        }
 
        Course::factory(10)
            ->published()
            ->create()
            ->each(fn ($course) => $course->update([
                'user_id' => $authors->random()->id,
            ]));
 
        Course::factory(5)
            ->draft()
            ->create()
            ->each(fn ($course) => $course->update([
                'user_id' => $authors->random()->id,
            ]));
 
        Course::factory(3)
            ->published()
            ->free()
            ->create()
            ->each(fn ($course) => $course->update([
                'user_id' => $authors->random()->id,
            ]));
    }
}
 