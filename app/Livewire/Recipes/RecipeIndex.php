<?php

namespace App\Livewire\Recipes;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class RecipeIndex extends Component
{
    use AuthorizesRequests;

    public function deleteRecipe(int $recipeId): void
    {
        $recipe = Auth::user()->recipes()->findOrFail($recipeId);

        $this->authorize('delete', $recipe);
        $recipe->delete();

        session()->flash('status', 'Das Rezept wurde gelöscht.');
    }

    public function render(): View
    {
        return view('livewire.recipes.index', [
            'recipes' => Auth::user()->recipes()
                ->withCount('ingredients')
                ->latest('updated_at')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Meine Rezepte']);
    }
}
