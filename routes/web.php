<?php

use App\Http\Controllers\BudgetPortionsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NewUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\HandleSafeSubmit;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\NewUser;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',  [DashboardController::class, 'index'])->middleware(['auth', 'verified', NewUser::class])->name('dashboard');
Route::get('/budgeting',  [BudgetPortionsController::class, 'index'])->middleware(['auth', 'verified', NewUser::class])->name('budgeting');
Route::get('/transactions',  [BudgetPortionsController::class, 'index'])->middleware(['auth', 'verified', NewUser::class])->name('transaction');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/welcome', [NewUserController::class, 'initializeUser'])->middleware(['auth', 'verified']);

Route::get('/new_user_setup', [NewUserController::class, 'index'])->middleware(['auth', 'verified'])->name('new_user.set-up');

Route::post('/default_portion', [NewUserController::class, 'newUserSetup'])->middleware(['auth', 'verified'])->name('new_user.submit');
Route::post('/edit_portion', [BudgetPortionsController::class, 'editPortion'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('save.edited-portion');
Route::post('/add_portion', [BudgetPortionsController::class, 'addPortion'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('save.added-portion');
Route::get('/new_portioning', [BudgetPortionsController::class, 'showPortion'])->middleware(['auth', 'verified'])->name('show.portion');

Route::post('/add_money_in', [TransactionsController::class, 'storeMoneyIn'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('add.money-in');
Route::post('/add_money_out', [TransactionsController::class, 'storeMoneyOut'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('add.money-out');
Route::post('/add_savings', [TransactionsController::class, 'storeSavings'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('add.savings');
require __DIR__ . '/auth.php';
