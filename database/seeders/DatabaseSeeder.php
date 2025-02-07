<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $users = User::inRandomOrder()->take(10)->get();

        foreach ($users as $user) {
            Customer::factory()->create([
                'customer_id' => $user->id, // تعيين المستخدم الفريد لكل عميل
            ]);
        }
    
    }
}
