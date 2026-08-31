<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class UserShow extends Component
{
    use AuthorizesRequests, WithPagination;

    #[Locked]
    public int $userId;

    public function mount(int $userId): void
    {
        $this->authorize('viewAny', User::class);

        $user = User::query()->findOrFail($userId);
        $this->authorize('view', $user);
        $this->userId = $user->id;
    }

    public function render(): View
    {
        $user = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at'])
            ->withCount('recipes')
            ->findOrFail($this->userId);

        $this->authorize('view', $user);

        return view('livewire.admin.user-show', [
            'user' => $user,
            'recipes' => $user->recipes()
                ->withCount('ingredients')
                ->latest('updated_at')
                ->paginate(12),
        ])->layout('layouts.app', ['title' => $user->name.' | Admin']);
    }
}
