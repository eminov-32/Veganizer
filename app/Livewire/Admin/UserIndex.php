<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use AuthorizesRequests, WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->search = mb_substr($this->search, 0, 100);
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function render(): View
    {
        $this->authorize('viewAny', User::class);

        $search = trim($this->search);

        $users = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at'])
            ->withCount('recipes')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest('created_at')
            ->latest('id')
            ->paginate(15);

        return view('livewire.admin.user-index', [
            'users' => $users,
            'searchTerm' => $search,
        ])->layout('layouts.app', ['title' => 'Benutzerkonten | Admin']);
    }
}
