<?php
 
namespace Database\Factories;
 
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
 
/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
 
    private static array $categories = [
        'Technology', 'Programming', 'Design', 'Business',
        'Marketing', 'Science', 'Health', 'Education',
        'Finance', 'Arts', 'Music', 'Sports',
    ];
 
    private static int $index = 0;
 
    public function definition(): array
    {
        $category = self::$categories[self::$index % count(self::$categories)];
        self::$index++;
 
        return [
            'category' => $category,
        ];
    }
}