<?php

namespace Modules\SuccessStories\Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Modules\SuccessStories\Models\SuccessStory;

class SuccessStoriesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create 20 fake success story entries
        for ($i = 0; $i < 20; $i++) {
            SuccessStory::create([
                'title' => $faker->sentence(6, true),
                'summary' => $faker->paragraph(1, true),
                'content' => $faker->paragraph(6, true),
                'image' => 'https://picsum.photos/640/480?random=' . $i,
                'author' => $faker->name(),
                'slug' => Str::slug($faker->unique()->sentence(3, true)),
                'status' => $faker->boolean(50),
            ]);
        }
    }
}
