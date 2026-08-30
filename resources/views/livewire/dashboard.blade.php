<x-app-shell
    eyebrow="Startseite"
    :title="'Hallo '.auth()->user()->name.' 👋'"
    description="Erstelle dein erstes Rezept oder arbeite an deinen gespeicherten Gerichten weiter."
>
    <x-slot:actions>
        <a wire:navigate href="{{ route('recipes.create') }}" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-vegan-leaf-dark px-5 py-3 font-bold text-white transition hover:bg-[#4f6623] focus:outline-none focus:ring-4 focus:ring-vegan-mist">
            Rezept erstellen
        </a>
    </x-slot:actions>

    <section aria-label="Schnellzugriff" class="grid gap-4 md:grid-cols-2">
        <a wire:navigate href="{{ route('recipes.create') }}" class="group rounded-2xl border border-vegan-line bg-[#fbfcf4] p-6 transition hover:-translate-y-0.5 hover:border-[#9caf70] hover:shadow-md focus:outline-none focus:ring-4 focus:ring-vegan-mist">
            <div class="flex items-start justify-between gap-5">
                <div>
                    <p class="text-sm font-bold text-vegan-ink">Neues Rezept anlegen</p>
                    <p class="mt-2 text-sm leading-relaxed text-[#5b675e]">Erfasse Zutaten, Mengen und die Zubereitung deines Gerichts.</p>
                </div>
                <span aria-hidden="true" class="text-2xl text-vegan-leaf-dark transition group-hover:translate-x-1">→</span>
            </div>
        </a>

        <a wire:navigate href="{{ route('recipes.index') }}" class="group rounded-2xl border border-vegan-line bg-[#fbfcf4] p-6 transition hover:-translate-y-0.5 hover:border-[#9caf70] hover:shadow-md focus:outline-none focus:ring-4 focus:ring-vegan-mist">
            <div class="flex items-start justify-between gap-5">
                <div>
                    <p class="text-sm font-bold text-vegan-ink">Meine Rezepte</p>
                    <p class="mt-2 text-sm leading-relaxed text-[#5b675e]">{{ $recipeCount }} {{ $recipeCount === 1 ? 'eigenes Rezept' : 'eigene Rezepte' }} gespeichert.</p>
                </div>
                <span aria-hidden="true" class="text-2xl text-vegan-leaf-dark transition group-hover:translate-x-1">→</span>
            </div>
        </a>
    </section>

    <section class="mt-8 rounded-2xl border border-vegan-line bg-white/70 p-5 sm:p-6" aria-labelledby="recent-recipes-heading">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-vegan-leaf-dark">Zuletzt bearbeitet</p>
                <h2 id="recent-recipes-heading" class="mt-1 text-xl font-bold text-vegan-ink">Deine letzten Rezepte</h2>
            </div>
            @if ($recentRecipes->isNotEmpty())
                <a wire:navigate href="{{ route('recipes.index') }}" class="rounded-lg px-2 py-1 text-sm font-bold text-vegan-leaf-dark underline decoration-transparent underline-offset-4 hover:decoration-current focus:outline-none focus:ring-2 focus:ring-vegan-leaf">Alle ansehen</a>
            @endif
        </div>

        @if ($recentRecipes->isEmpty())
            <div class="py-10 text-center">
                <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-vegan-mist">
                    <x-icon-sprout class="h-11 w-11" />
                </div>
                <p class="mt-4 font-bold text-vegan-ink">Noch kein Rezept gespeichert</p>
                <p class="mt-2 text-sm text-[#5b675e]">Dein erstes Rezept ist nur ein paar Zutaten entfernt.</p>
            </div>
        @else
            <div class="mt-5 grid gap-3 lg:grid-cols-3">
                @foreach ($recentRecipes as $recipe)
                    <a wire:navigate href="{{ route('recipes.show', $recipe) }}" class="rounded-xl border border-vegan-line bg-vegan-paper p-4 transition hover:border-[#9caf70] hover:shadow-sm focus:outline-none focus:ring-4 focus:ring-vegan-mist">
                        <p class="font-bold text-vegan-ink">{{ $recipe->title }}</p>
                        <p class="mt-2 text-sm text-[#677168]">{{ $recipe->ingredients_count }} {{ $recipe->ingredients_count === 1 ? 'Zutat' : 'Zutaten' }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</x-app-shell>
