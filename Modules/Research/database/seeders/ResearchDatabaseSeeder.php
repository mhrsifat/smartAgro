<?php

namespace Modules\Research\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Research\Models\Research; // Make sure your model namespace is correct
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ResearchDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create 20 fake research entries
        for ($i = 0; $i < 20; $i++) {
            Research::create([
                'title' => $faker->sentence(6, true),
                'description' => $faker->paragraph(5, true),
                'slug' => Str::slug($faker->unique()->sentence(3, true)),
                'image' => $faker->imageUrl(640, 480, 'science', true),
                'authors' => json_encode([$faker->name(), $faker->name()]),
                'status' => $faker->randomElement(['draft', 'under_review', 'published']),
                'is_featured' => $faker->boolean(20), // 20% chance to be featured
                'download_url' => $faker->url(),
                'user_id' => null, // Or assign random user ID if you have users
            ]);
        }
    }
}