<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;

class RecipePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Recipe $recipe): bool
    {
        return $recipe->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Recipe $recipe): bool
    {
        return $recipe->user_id === $user->id;
    }

    public function delete(User $user, Recipe $recipe): bool
    {
        return $recipe->user_id === $user->id;
    }

    public function inspect(User $user, Recipe $recipe): bool
    {
        return $user->isAdmin();
    }
}
