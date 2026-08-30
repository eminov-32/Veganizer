<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        $user = Auth::user();

        return view('livewire.dashboard', [
            'recipeCount' => $user->recipes()->count(),
            'recentRecipes' => $user->recipes()
                ->withCount('ingredients')
                ->latest('updated_at')
                ->limit(3)
                ->get(),
        ])
            ->layout('layouts.app', ['title' => 'Startseite']);
    }
}
