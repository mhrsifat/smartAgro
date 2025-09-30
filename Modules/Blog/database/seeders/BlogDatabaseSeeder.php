<?php

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\Blog;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class BlogDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all user IDs
        $userIds = User::pluck('id')->toArray();

        // Generate 20 fake blogs
        for ($i = 0; $i < 20; $i++) {
            Blog::create([
                'title' => $faker->sentence(),
                'content' => $faker->paragraphs(5, true),
                'excerpt' => $faker->paragraph(),
                'slug' => Str::slug($faker->sentence()) . '-' . $faker->unique()->numberBetween(1, 1000),
                'image' => 'https://picsum.photos/640/480?random=' . $i,
                'author_id' => $faker->randomElement($userIds),
                'status' => 'published',
            ]);
        }
        
        // $this->call([]);
    }
}