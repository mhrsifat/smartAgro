<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Blog\Database\Seeders\BlogDatabaseSeeder;
use Modules\Research\Database\Seeders\ResearchDatabaseSeeder;
use Modules\Donation\Database\Seeders\DonationDatabaseSeeder;
use Modules\SuccessStories\Database\Seeders\SuccessStoriesDatabaseSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        // $this->call(UserSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(BlogDatabaseSeeder::class);
        $this->call(ResearchDatabaseSeeder::class);
        $this->call(DonationDatabaseSeeder::class);
        $this->call(SuccessStoriesDatabaseSeeder::class);

        /** User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
        ]); */
    }
}