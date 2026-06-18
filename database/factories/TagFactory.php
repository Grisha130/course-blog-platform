<?php
 
namespace Database\Factories;
 
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
 
/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    protected $model = Tag::class;
 
    private static array $tags = [
        'laravel', 'php', 'javascript', 'vue', 'react',
        'mysql', 'api', 'docker', 'linux', 'python',
        'css', 'html', 'nodejs', 'typescript', 'git',
        'devops', 'aws', 'testing', 'security', 'performance',
    ];
 
    private static int $index = 0;
 
    public function definition(): array
    {
        $tag = self::$tags[self::$index % count(self::$tags)];
        self::$index++;
 
        return [
            'tag' => $tag,
        ];
    }
}