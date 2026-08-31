<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RecipeShow extends Component
{
    use AuthorizesRequests;

    #[Locked]
    public int $userId;

    #[Locked]
    public int $recipeId;

    public function mount(int $userId, int $recipeId): void
    {
        $this->authorize('viewAny', User::class);

        $user = User::query()->findOrFail($userId);
        $this->authorize('view', $user);

        $recipe = $user->recipes()->findOrFail($recipeId);
        $this->authorize('inspect', $recipe);

        $this->userId = $user->id;
        $this->recipeId = $recipe->id;
    }

    public function render(): View
    {
        $user = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at'])
            ->findOrFail($this->userId);

        $this->authorize('view', $user);

        $recipe = $user->recipes()
            ->with('ingredients')
            ->findOrFail($this->recipeId);

        $this->authorize('inspect', $recipe);

        return view('livewire.admin.recipe-show', [
            'user' => $user,
            'recipe' => $recipe,
        ])->layout('layouts.app', ['title' => $recipe->title.' | Admin']);
    }
}
