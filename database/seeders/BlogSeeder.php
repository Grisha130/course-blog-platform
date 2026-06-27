<?php
 
namespace Database\Seeders;
 
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
 
class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $authors    = User::whereHas('roles')->get();
        $categories = Category::all();
        $tags       = Tag::all();
 
        if ($authors->isEmpty()) {
            $authors = User::all();
        }
 
        Blog::factory(12)
            ->published()
            ->create([
                'user_id'     => fn () => $authors->random()->id,     
                'category_id' => fn () => $categories->random()->id,  
            ])
            ->each(function (Blog $blog) use ($tags) {
                if ($tags->isNotEmpty()) {
                    $blog->tags()->attach(
                        $tags->random(rand(2, 4))->pluck('id')->toArray()
                    );
                }
            });
 
        Blog::factory(5)
            ->draft()
            ->create([
                'user_id'     => fn () => $authors->random()->id,
                'category_id' => fn () => $categories->random()->id,
            ])
            ->each(function (Blog $blog) use ($tags) {
                if ($tags->isNotEmpty()) {
                    $blog->tags()->attach(
                        $tags->random(rand(1, 3))->pluck('id')->toArray()
                    );
                }
            });
    }
}