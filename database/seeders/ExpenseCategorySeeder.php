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
            'name' => 'FOOD',
        ]);

        DB::table('expenses_category')->insert([
            'name' => 'GAS',
        ]);

        DB::table('expenses_category')->insert([
            'name' => 'SAVINGS',
        ]);

    }
}
