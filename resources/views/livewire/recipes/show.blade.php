<x-app-shell
    eyebrow="Privates Rezept"
    :title="$recipe->title"
    :description="$recipe->description"
>
    <x-slot:actions>
        <a wire:navigate href="{{ route('recipes.edit', $recipe) }}" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-vegan-leaf-dark px-5 py-3 font-bold text-white transition hover:bg-[#4f6623] focus:outline-none focus:ring-4 focus:ring-vegan-mist">
            Rezept bearbeiten
        </a>
    </x-slot:actions>

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <a wire:navigate href="{{ route('recipes.index') }}" class="rounded-lg text-sm font-bold text-vegan-leaf-dark underline decoration-transparent underline-offset-4 hover:decoration-current focus:outline-none focus:ring-2 focus:ring-vegan-leaf">← Zurück zu meinen Rezepten</a>
        <span class="rounded-full bg-vegan-mist px-3 py-1.5 text-xs font-bold text-vegan-leaf-dark">Nur für dich sichtbar</span>
    </div>

    <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)]">
        <section class="rounded-2xl border border-vegan-line bg-[#fbfcf4] p-5 sm:p-7" aria-labelledby="recipe-ingredients-heading">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-vegan-leaf-dark">Zutaten</p>
                    <h2 id="recipe-ingredients-heading" class="mt-1 text-2xl font-bold text-vegan-ink">Das brauchst du</h2>
                </div>
                <x-icon-sprout class="h-12 w-12" />
            </div>

            <dl class="mt-5 grid grid-cols-2 gap-3 text-sm sm:grid-cols-3">
                @if ($recipe->servings)
                    <div class="rounded-xl bg-white p-3 ring-1 ring-vegan-line">
                        <dt class="text-[#6b756d]">Portionen</dt>
                        <dd class="mt-1 font-bold text-vegan-ink">{{ $recipe->servings }}</dd>
                    </div>
                @endif
                @if ($recipe->prep_minutes)
                    <div class="rounded-xl bg-white p-3 ring-1 ring-vegan-line">
                        <dt class="text-[#6b756d]">Vorbereitung</dt>
                        <dd class="mt-1 font-bold text-vegan-ink">{{ $recipe->prep_minutes }} Min.</dd>
                    </div>
                @endif
                @if ($recipe->cook_minutes)
                    <div class="rounded-xl bg-white p-3 ring-1 ring-vegan-line">
                        <dt class="text-[#6b756d]">Kochzeit</dt>
                        <dd class="mt-1 font-bold text-vegan-ink">{{ $recipe->cook_minutes }} Min.</dd>
                    </div>
                @endif
            </dl>

            <ol class="mt-6 space-y-3">
                @foreach ($recipe->ingredients as $ingredient)
                    <li class="flex gap-3 rounded-xl border border-vegan-line bg-white p-4">
                        <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-vegan-mist text-xs font-bold text-vegan-leaf-dark">{{ $loop->iteration }}</span>
                        <div>
                            <p class="font-bold text-vegan-ink">
                                @if ($ingredient->amount || $ingredient->unit)
                                    <span class="text-vegan-leaf-dark">{{ trim($ingredient->amount.' '.$ingredient->unit) }}</span>
                                @endif
                                {{ $ingredient->name }}
                            </p>
                            @if ($ingredient->notes)
                                <p class="mt-1 text-sm text-[#687269]">{{ $ingredient->notes }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        <section class="rounded-2xl border border-vegan-line bg-white p-5 sm:p-7" aria-labelledby="recipe-instructions-heading">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-vegan-leaf-dark">Zubereitung</p>
            <h2 id="recipe-instructions-heading" class="mt-1 text-2xl font-bold text-vegan-ink">So wird es gemacht</h2>
            <div class="mt-5 whitespace-pre-line text-base leading-8 text-[#455249]">{{ $recipe->instructions }}</div>

            <div class="mt-8 flex flex-wrap gap-3 border-t border-vegan-line pt-6">
                <a wire:navigate href="{{ route('recipes.edit', $recipe) }}" class="inline-flex min-h-11 items-center rounded-xl border border-vegan-line px-4 py-2 text-sm font-bold text-vegan-leaf-dark transition hover:bg-vegan-mist focus:outline-none focus:ring-4 focus:ring-vegan-mist">Bearbeiten</a>
                <button
                    type="button"
                    wire:click="deleteRecipe"
                    wire:confirm="Möchtest du „{{ $recipe->title }}“ wirklich löschen?"
                    class="inline-flex min-h-11 items-center rounded-xl border border-red-200 px-4 py-2 text-sm font-bold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-100"
                >
                    Rezept löschen
                </button>
            </div>
        </section>
    </div>
</x-app-shell>
