<?php
 
namespace Database\Seeders;
 
use App\Models\Category;
use Illuminate\Database\Seeder;
 
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Technology', 'Programming', 'Design', 'Business',
            'Marketing', 'Science', 'Health', 'Education',
            'Finance', 'Arts',
        ];
 
        foreach ($categories as $name) {
            Category::firstOrCreate(['category' => $name]);
        }
    }
}