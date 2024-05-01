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
        DB::table('expenses_category')->insert([
            'name' => 'Food',
        ]);

        DB::table('expenses_category')->insert([
            'name' => 'Bills',
        ]);

        DB::table('expenses_category')->insert([
            'name' => 'Internet',
        ]);

        DB::table('expenses_category')->insert([
            'name' => 'Entertainment',
        ]);

        DB::table('expenses_category')->insert([
            'name' => 'Shopping',
        ]);

        DB::table('expenses_category')->insert([
            'name' => 'Savings',
        ]);

    }
}
