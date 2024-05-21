<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetPortionsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InsightsController;
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
Route::get('/transactions',  [TransactionsController::class, 'index'])->middleware(['auth', 'verified', NewUser::class])->name('transactions');
Route::get('/insights',  [InsightsController::class, 'index'])->middleware(['auth', 'verified', NewUser::class])->name('insights');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/welcome', [NewUserController::class, 'initializeUser'])->middleware(['auth', 'verified']);

Route::get('/new_user_setup', [NewUserController::class, 'index'])->middleware(['auth', 'verified'])->name('new_user.set-up');

Route::post('/default_portion', [NewUserController::class, 'newUserSetup'])->middleware(['auth', 'verified'])->name('new_user.submit');
Route::post('/edit_portion', [BudgetPortionsController::class, 'newUserEditPortion'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('save.edited-portion');
Route::post('/add_portion', [BudgetPortionsController::class, 'newUserAddPortion'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('save.added-portion');
Route::get('/new_portioning', [BudgetPortionsController::class, 'showPortion'])->middleware(['auth', 'verified'])->name('show.portion');

Route::post('/add_money_in', [TransactionsController::class, 'goToDashboardMoneyIn'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('add.money-in');
Route::post('/add_money_out', [TransactionsController::class, 'goToDashboard'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('add.money-out');
Route::post('/add_savings', [TransactionsController::class, 'storeSavings'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('add.savings');
Route::post('/budgeting_add_expense', [TransactionsController::class, 'goToBudgeting'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('budgeting.add-expense');

Route::post('/edit_budget', [BudgetController::class, 'update'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('edit.budget');
Route::post('/budgeting_edit_portion', [BudgetPortionsController::class, 'budgetingEditPortion'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('budgeting.edit-portion');
Route::post('/budgeting_add_portion', [BudgetPortionsController::class, 'budgetingAddPortion'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('budgeting.add-portion');


Route::get('/search',  [TransactionsController::class, 'search'])->middleware(['auth', 'verified', NewUser::class])->name('search.transaction');
Route::get('/filter_transctions_by_date',  [TransactionsController::class, 'filterByDate'])->middleware(['auth', 'verified', NewUser::class])->name('filter.bydate');
Route::post('/transactions_add_money_in', [TransactionsController::class, 'goToTransactionsMoneyIn'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('transactions.money-in');
Route::post('/transactions_add_money_out', [TransactionsController::class, 'goToTransactionsMoneyOut'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('transactions.money-out');
require __DIR__ . '/auth.php';
