<?php

namespace App\Livewire\Recipes;

use App\Models\Recipe;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RecipeShow extends Component
{
    use AuthorizesRequests;

    #[Locked]
    public int $recipeId;

    public function mount(Recipe $recipe): void
    {
        abort_unless($recipe->user_id === Auth::id(), 404);
        $this->authorize('view', $recipe);

        $this->recipeId = $recipe->id;
    }

    public function deleteRecipe(): void
    {
        $recipe = Auth::user()->recipes()->findOrFail($this->recipeId);

        $this->authorize('delete', $recipe);
        $recipe->delete();

        session()->flash('status', 'Das Rezept wurde gelöscht.');
        $this->redirect(route('recipes.index'), navigate: true);
    }

    public function render(): View
    {
        $recipe = Auth::user()->recipes()
            ->with('ingredients')
            ->findOrFail($this->recipeId);

        $this->authorize('view', $recipe);

        return view('livewire.recipes.show', [
            'recipe' => $recipe,
        ])->layout('layouts.app', ['title' => $recipe->title]);
    }
}
