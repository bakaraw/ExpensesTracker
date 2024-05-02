<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('expense_categories', function (Blueprint $table){
            $table->unsignedSmallInteger('id')->primary()->autoIncrement();
            $table->string('name', 255);
        });

        Schema::create('budget_portions', function (Blueprint $table) {
            $table->id('portion_id');

            $table->unsignedBigInteger('budget_id');
            $table->foreign('budget_id')->references('budget_id')->on('user_budgets');

            $table->unsignedSmallInteger('category');
            $table->foreign('category')->references('id')->on('expense_categories');

            $table->decimal('portion', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_portions');
        Schema::dropIfExists('expense_categories');
    }
};
