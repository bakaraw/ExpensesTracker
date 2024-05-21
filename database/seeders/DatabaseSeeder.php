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


        $money_in = 0;
        $money_out =1;
        $savings_category_id = 6;

        for ($i = 0; $i < 53; $i++) {

            //inserting money-in
            DB::table('transactions')->insert([
                'user_id' => 1,
                'note' => "none",
                'amount' => rand(300, 900),
                'is_money_out' => $money_in,
                'created_at' => Carbon::now()->subWeeks($i),
                'updated_at' => Carbon::now()->subWeeks($i),
            ]);

            //inserting money-out
            DB::table('transactions')->insert([
                'user_id' => 1,
                'note' => "none",
                'amount' => rand(300, 900),
                'category_id' => rand(1, 5),
                'is_money_out' => $money_out,
                'created_at' => Carbon::now()->subWeeks($i),
                'updated_at' => Carbon::now()->subWeeks($i),
            ]);

            // inserting savings
            DB::table('transactions')->insert([
                'user_id' => 1,
                'note' => "none",
                'amount' => rand(50, 100),
                'category_id' => $savings_category_id,
                'is_money_out' => $money_out,
                'created_at' => Carbon::now()->subWeeks($i),
                'updated_at' => Carbon::now()->subWeeks($i),
            ]);
        }
    }
}
