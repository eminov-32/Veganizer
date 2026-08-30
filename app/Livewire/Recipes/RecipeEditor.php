<?php

namespace App\Livewire\Recipes;

use App\Models\Recipe;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RecipeEditor extends Component
{
    use AuthorizesRequests;

    #[Locked]
    public ?int $recipeId = null;

    public string $title = '';

    public string $description = '';

    public string $instructions = '';

    public ?int $servings = null;

    public ?int $prepMinutes = null;

    public ?int $cookMinutes = null;

    /** @var array<int, array{key: string, amount: string, unit: string, name: string, notes: string}> */
    public array $ingredients = [];

    public function mount(?Recipe $recipe = null): void
    {
        if ($recipe?->exists) {
            abort_unless($recipe->user_id === Auth::id(), 404);
            $this->authorize('update', $recipe);

            $recipe->load('ingredients');
            $this->recipeId = $recipe->id;
            $this->title = $recipe->title;
            $this->description = $recipe->description ?? '';
            $this->instructions = $recipe->instructions;
            $this->servings = $recipe->servings;
            $this->prepMinutes = $recipe->prep_minutes;
            $this->cookMinutes = $recipe->cook_minutes;
            $this->ingredients = $recipe->ingredients
                ->map(fn ($ingredient): array => [
                    'key' => (string) Str::uuid(),
                    'amount' => $ingredient->amount ?? '',
                    'unit' => $ingredient->unit ?? '',
                    'name' => $ingredient->name,
                    'notes' => $ingredient->notes ?? '',
                ])
                ->all();
        }

        if ($this->ingredients === []) {
            $this->addIngredient();
        }
    }

    public function addIngredient(): void
    {
        if (count($this->ingredients) >= 30) {
            return;
        }

        $this->ingredients[] = [
            'key' => (string) Str::uuid(),
            'amount' => '',
            'unit' => '',
            'name' => '',
            'notes' => '',
        ];
    }

    public function removeIngredient(int $index): void
    {
        if (! array_key_exists($index, $this->ingredients)) {
            return;
        }

        if (count($this->ingredients) === 1) {
            $this->ingredients[0] = [
                ...$this->ingredients[0],
                'amount' => '',
                'unit' => '',
                'name' => '',
                'notes' => '',
            ];

            return;
        }

        unset($this->ingredients[$index]);
        $this->ingredients = array_values($this->ingredients);
    }

    public function save(): void
    {
        $this->normalizeInput();

        $validated = $this->validate([
            'title' => ['required', 'string', 'min:2', 'max:160'],
            'description' => ['nullable', 'string', 'max:1000'],
            'instructions' => ['required', 'string', 'min:10', 'max:20000'],
            'servings' => ['nullable', 'integer', 'min:1', 'max:100'],
            'prepMinutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'cookMinutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'ingredients' => ['required', 'array', 'min:1', 'max:30'],
            'ingredients.*.name' => ['required', 'string', 'max:120'],
            'ingredients.*.amount' => ['nullable', 'string', 'max:40'],
            'ingredients.*.unit' => ['nullable', 'string', 'max:40'],
            'ingredients.*.notes' => ['nullable', 'string', 'max:255'],
        ], [
            'title.required' => 'Bitte gib dem Rezept einen Namen.',
            'title.min' => 'Der Rezeptname muss mindestens 2 Zeichen lang sein.',
            'instructions.required' => 'Bitte beschreibe die Zubereitung.',
            'instructions.min' => 'Die Zubereitung muss mindestens 10 Zeichen lang sein.',
            'servings.min' => 'Die Anzahl der Portionen muss mindestens 1 sein.',
            'ingredients.required' => 'Füge mindestens eine Zutat hinzu.',
            'ingredients.min' => 'Füge mindestens eine Zutat hinzu.',
            'ingredients.max' => 'Ein Rezept kann höchstens 30 Zutaten enthalten.',
            'ingredients.*.name.required' => 'Bitte gib für jede Zeile eine Zutat ein.',
        ]);

        $recipe = DB::transaction(function () use ($validated): Recipe {
            if ($this->recipeId !== null) {
                $recipe = Auth::user()->recipes()->findOrFail($this->recipeId);
                $this->authorize('update', $recipe);
                $recipe->update($this->recipeData($validated));
                $recipe->ingredients()->delete();
            } else {
                $this->authorize('create', Recipe::class);
                $recipe = Auth::user()->recipes()->create($this->recipeData($validated));
            }

            $recipe->ingredients()->createMany(
                collect($validated['ingredients'])
                    ->values()
                    ->map(fn (array $ingredient, int $position): array => [
                        'name' => $ingredient['name'],
                        'amount' => $ingredient['amount'] ?: null,
                        'unit' => $ingredient['unit'] ?: null,
                        'notes' => $ingredient['notes'] ?: null,
                        'position' => $position,
                    ])
                    ->all()
            );

            return $recipe;
        });

        session()->flash(
            'status',
            $this->recipeId === null ? 'Dein Rezept wurde gespeichert.' : 'Deine Änderungen wurden gespeichert.'
        );

        $this->redirect(route('recipes.show', $recipe), navigate: true);
    }

    private function normalizeInput(): void
    {
        $this->title = trim($this->title);
        $this->description = trim($this->description);
        $this->instructions = trim($this->instructions);

        $this->ingredients = collect($this->ingredients)
            ->map(fn (array $ingredient): array => [
                'key' => $ingredient['key'] ?? (string) Str::uuid(),
                'amount' => trim((string) ($ingredient['amount'] ?? '')),
                'unit' => trim((string) ($ingredient['unit'] ?? '')),
                'name' => trim((string) ($ingredient['name'] ?? '')),
                'notes' => trim((string) ($ingredient['notes'] ?? '')),
            ])
            ->values()
            ->all();
    }

    /** @return array<string, mixed> */
    private function recipeData(array $validated): array
    {
        return [
            'title' => $validated['title'],
            'description' => $validated['description'] ?: null,
            'instructions' => $validated['instructions'],
            'servings' => $validated['servings'],
            'prep_minutes' => $validated['prepMinutes'],
            'cook_minutes' => $validated['cookMinutes'],
        ];
    }

    public function render(): View
    {
        return view('livewire.recipes.editor', [
            'isEditing' => $this->recipeId !== null,
            'cancelUrl' => $this->recipeId === null
                ? route('recipes.index')
                : route('recipes.show', $this->recipeId),
        ])->layout('layouts.app', [
            'title' => $this->recipeId === null ? 'Neues Rezept' : 'Rezept bearbeiten',
        ]);
    }
}
