<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('budget_type')->insert([
            'name' => 'daily',
        ]);

        DB::table('budget_type')->insert([
            'name' => 'weekly',
        ]);

        DB::table('budget_type')->insert([
            'name' => 'monthly',
        ]);


    }
}
