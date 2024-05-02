<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('expense_categories')->insert([
            'name' => 'Food',
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Bills',
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Internet',
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Entertainment',
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Shopping',
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Savings',
        ]);

    }
}
