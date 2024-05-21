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
            'icon' => 'fas fa-utensils'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Bills',
            'icon' => 'fas fa-money-bill'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Internet',
            'icon' => 'fas fa-wifi'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Entertainment',
            'icon' => 'fas fa-film'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Shopping',
            'icon' => 'fas fa-shopping-bag'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Savings',
            'icon' => 'fas fa-piggy-bank'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Baby Stuff',
            'icon' => 'fas fa-baby-carriage'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Business Expense',
            'icon' => 'fas fa-briefcase'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Car',
            'icon' => 'fas fa-car'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Delivery Services',
            'icon' => 'fas fa-truck'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Credit Card Payment',
            'icon' => 'fas fa-credit-card'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Educational',
            'icon' => 'fas fa-school'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Rent',
            'icon' => 'fas fa-building'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Tax',
            'icon' => 'fas fa-money-check'
        ]);

        DB::table('expense_categories')->insert([
            'name' => 'Uncategorized',
            'icon' => 'fas fa-question'
        ]);
    }
}
