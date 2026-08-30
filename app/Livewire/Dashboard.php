<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        return view('livewire.dashboard')
            ->layout('layouts.app', ['title' => 'Startseite']);
    }
}
