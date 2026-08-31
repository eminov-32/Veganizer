<?php

use App\Livewire\Admin\RecipeShow as AdminRecipeShow;
use App\Livewire\Admin\UserIndex as AdminUserIndex;
use App\Livewire\Admin\UserShow as AdminUserShow;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard;
use App\Livewire\Recipes\RecipeEditor;
use App\Livewire\Recipes\RecipeIndex;
use App\Livewire\Recipes\RecipeShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware(['auth', 'can:access-admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::redirect('/', '/admin/users')->name('dashboard');
        Route::get('/users', AdminUserIndex::class)->name('users.index');
        Route::get('/users/{userId}', AdminUserShow::class)->whereNumber('userId')->name('users.show');
        Route::get('/users/{userId}/recipes/{recipeId}', AdminRecipeShow::class)
            ->whereNumber(['userId', 'recipeId'])
            ->name('users.recipes.show');
    });

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/recipes', RecipeIndex::class)->name('recipes.index');
    Route::get('/recipes/create', RecipeEditor::class)->name('recipes.create');
    Route::get('/recipes/{recipe}', RecipeShow::class)->name('recipes.show');
    Route::get('/recipes/{recipe}/edit', RecipeEditor::class)->name('recipes.edit');

    Route::post('/logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
