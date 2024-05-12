<?php

namespace Database\Seeders;

use App\Models\Transactions;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            BudgetTypesSeeder::class,
            ExpenseCategorySeeder::class
        ]);

        for($i = 0; $i < 53; $i++){
            DB::table('transactions')->insert([
                'user_id' => 1,
                'note' => "none",
                'amount' => rand(300, 900),
                'is_money_out' => 0,
                'created_at' => Carbon::now()->subWeeks($i),
                'updated_at' => Carbon::now()->subWeeks($i),
               ]);

               DB::table('transactions')->insert([
                'user_id' => 1,
                'note' => "none",
                'amount' => rand(300, 900),
                'category_id' => rand(1, 6),
                'is_money_out' => 1,
                'created_at' => Carbon::now()->subWeeks($i),
                'updated_at' => Carbon::now()->subWeeks($i),
               ]);
        }

    }
}
