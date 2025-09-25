<?php

namespace Modules\Donation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Donation\Models\Donation;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DonationDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Create 10 sample donations
        for ($i = 1; $i <= 10; $i++) {
            Donation::create([
                'user_id'        => null, // guest donation
                'donor_name'     => $faker->name,
                'donor_email'    => $faker->unique()->safeEmail,
                'donor_phone'    => '01' . $faker->numberBetween(3, 9) . $faker->numberBetween(100000000, 999999999),
                'amount'         => $faker->numberBetween(100, 5000),
                'currency'       => 'BDT',
                'message'        => $faker->sentence(8),
                'anonymous'      => $faker->boolean(50), // ~50% anonymous
                'payment_gateway'=> $faker->randomElement(['bkash', 'nagad', 'sslcommerz']),
                'transaction_id' => strtoupper(Str::random(10)),
                'status'         => $faker->randomElement(['pending', 'completed', 'failed']),
            ]);
        }
    }
}