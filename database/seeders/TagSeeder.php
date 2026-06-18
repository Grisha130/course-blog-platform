<?php
 
namespace Database\Seeders;
 
use App\Models\Tag;
use Illuminate\Database\Seeder;
 
class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'laravel', 'php', 'javascript', 'vue', 'react',
            'mysql', 'api', 'docker', 'linux', 'python',
            'css', 'html', 'nodejs', 'typescript', 'git',
            'devops', 'aws', 'testing', 'security', 'performance',
        ];
 
        foreach ($tags as $name) {
            Tag::firstOrCreate(['tag' => $name]);
        }
    }
}