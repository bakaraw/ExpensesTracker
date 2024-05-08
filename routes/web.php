<?php

use App\Http\Controllers\BudgetPortionsController;
use App\Http\Controllers\NewUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\HandleSafeSubmit;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\NewUser;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', 'DashboardController@index')->middleware(['auth', 'verified', NewUser::class])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/welcome', [NewUserController::class, 'initializeUser'])->middleware(['auth', 'verified']);

Route::get('/new_user_setup', [NewUserController::class, 'index'])->middleware(['auth', 'verified'])->name('new_user.set-up');

// Route::post('/portion_your_budget', [BudgetPortionsController::class, 'newUserPortion'])->middleware(['auth', 'verified'])->name('portion.budget');

Route::post('/default_portion', [NewUserController::class, 'newUserSetup'])->middleware(['auth', 'verified'])->name('new_user.submit');
Route::post('/edit_portion', [BudgetPortionsController::class, 'editPortion'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('save.edited-portion');
Route::post('/add_portion', [BudgetPortionsController::class, 'addPortion'])->middleware(['auth', 'verified', HandleSafeSubmit::class])->name('save.added-portion');
Route::get('/new_portioning', [BudgetPortionsController::class, 'showPortion'])->middleware(['auth', 'verified'])->name('show.portion');


require __DIR__.'/auth.php';
