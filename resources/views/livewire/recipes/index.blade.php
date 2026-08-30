<x-app-shell
    eyebrow="Meine Gerichte"
    title="Meine gespeicherten Rezepte"
    :description="$recipes->count() === 1 ? 'Du hast ein privates Rezept gespeichert.' : 'Du hast '.$recipes->count().' private Rezepte gespeichert.'"
>
    <x-slot:actions>
        <a wire:navigate href="{{ route('recipes.create') }}" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-vegan-leaf-dark px-5 py-3 font-bold text-white transition hover:bg-[#4f6623] focus:outline-none focus:ring-4 focus:ring-vegan-mist">
            + Neues Rezept
        </a>
    </x-slot:actions>

    @if ($recipes->isEmpty())
        <section class="rounded-2xl border border-dashed border-[#b7c198] bg-[#fbfcf4] px-6 py-14 text-center">
            <div class="mx-auto flex size-20 items-center justify-center rounded-full bg-vegan-mist">
                <x-icon-sprout class="h-14 w-14" />
            </div>
            <h2 class="mt-5 text-2xl font-bold text-vegan-ink">Deine Sammlung wartet auf das erste Rezept</h2>
            <p class="mx-auto mt-3 max-w-lg text-[#5b675e]">Lege Zutaten und Zubereitung an. Das Rezept bleibt zunächst nur für dich sichtbar.</p>
            <a wire:navigate href="{{ route('recipes.create') }}" class="mt-6 inline-flex min-h-12 items-center rounded-xl bg-vegan-leaf-dark px-5 py-3 font-bold text-white transition hover:bg-[#4f6623] focus:outline-none focus:ring-4 focus:ring-vegan-mist">
                Erstes Rezept erstellen
            </a>
        </section>
    @else
        <section aria-label="Rezeptliste" class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($recipes as $recipe)
                <article wire:key="recipe-{{ $recipe->id }}" class="flex min-h-64 flex-col rounded-2xl border border-vegan-line bg-[#fffefa] p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <span class="rounded-full bg-vegan-mist px-3 py-1 text-xs font-bold text-vegan-leaf-dark">Privat</span>
                        <x-icon-sprout class="h-10 w-10" />
                    </div>

                    <h2 class="mt-4 text-xl font-bold text-vegan-ink">
                        <a wire:navigate href="{{ route('recipes.show', $recipe) }}" class="rounded focus:outline-none focus:ring-2 focus:ring-vegan-leaf">{{ $recipe->title }}</a>
                    </h2>
                    <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-[#606b62]">
                        {{ $recipe->description ?: 'Keine Beschreibung hinterlegt.' }}
                    </p>

                    <div class="mt-auto pt-5">
                        <p class="text-sm text-[#687269]">{{ $recipe->ingredients_count }} {{ $recipe->ingredients_count === 1 ? 'Zutat' : 'Zutaten' }} · bearbeitet {{ $recipe->updated_at->diffForHumans() }}</p>
                        <div class="mt-4 flex flex-wrap gap-2 border-t border-vegan-line pt-4">
                            <a wire:navigate href="{{ route('recipes.show', $recipe) }}" class="inline-flex min-h-11 items-center rounded-xl border border-vegan-line px-4 py-2 text-sm font-bold text-vegan-leaf-dark transition hover:bg-vegan-mist focus:outline-none focus:ring-4 focus:ring-vegan-mist">Anzeigen</a>
                            <a wire:navigate href="{{ route('recipes.edit', $recipe) }}" class="inline-flex min-h-11 items-center rounded-xl border border-vegan-line px-4 py-2 text-sm font-bold text-vegan-leaf-dark transition hover:bg-vegan-mist focus:outline-none focus:ring-4 focus:ring-vegan-mist">Bearbeiten</a>
                            <button
                                type="button"
                                wire:click="deleteRecipe({{ $recipe->id }})"
                                wire:confirm="Möchtest du „{{ $recipe->title }}“ wirklich löschen?"
                                class="inline-flex min-h-11 items-center rounded-xl border border-red-200 px-4 py-2 text-sm font-bold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-100"
                            >
                                Löschen
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    @endif
</x-app-shell>
