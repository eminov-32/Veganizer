<x-app-shell
    eyebrow="Admin · Rezeptansicht"
    :title="$recipe->title"
    :description="$recipe->description"
>
    <x-slot:actions>
        <span class="inline-flex min-h-11 items-center rounded-full bg-vegan-mist px-4 py-2 text-sm font-bold text-vegan-ink">
            Nur lesen
        </span>
    </x-slot:actions>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <a wire:navigate href="{{ route('admin.users.show', ['userId' => $user->id]) }}" class="inline-flex min-h-11 min-w-0 items-center break-words rounded-lg text-sm font-bold text-vegan-leaf-dark underline decoration-transparent underline-offset-4 [overflow-wrap:anywhere] hover:decoration-current focus:outline-none focus:ring-2 focus:ring-vegan-leaf">
            ← Zurück zum Profil von {{ $user->name }}
        </a>
        <div class="min-w-0 text-sm text-[#637067] sm:text-right">
            <p>Gespeichert von <span class="font-bold text-vegan-ink">{{ $user->name }}</span></p>
            <p class="break-all">{{ $user->email }}</p>
        </div>
    </div>

    <div class="mt-6 grid items-start gap-6 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)]">
        <section class="rounded-2xl border border-vegan-line bg-[#fbfcf4] p-5 sm:p-7" aria-labelledby="admin-recipe-ingredients-heading">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-vegan-leaf-dark">Zutaten</p>
                    <h2 id="admin-recipe-ingredients-heading" class="mt-1 text-2xl font-bold text-vegan-ink">Das wird benötigt</h2>
                </div>
                <x-icon-sprout class="h-12 w-12" />
            </div>

            @if ($recipe->servings || $recipe->prep_minutes || $recipe->cook_minutes)
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
            @endif

            <ol class="mt-6 space-y-3">
                @foreach ($recipe->ingredients as $ingredient)
                    <li class="flex gap-3 rounded-xl border border-vegan-line bg-white p-4">
                        <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-vegan-mist text-xs font-bold text-vegan-ink">{{ $loop->iteration }}</span>
                        <div class="min-w-0">
                            <p class="break-words font-bold text-vegan-ink">
                                @if ($ingredient->amount || $ingredient->unit)
                                    <span class="text-vegan-leaf-dark">{{ trim($ingredient->amount.' '.$ingredient->unit) }}</span>
                                @endif
                                {{ $ingredient->name }}
                            </p>
                            @if ($ingredient->notes)
                                <p class="mt-1 break-words text-sm text-[#687269]">{{ $ingredient->notes }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        <section class="rounded-2xl border border-vegan-line bg-white p-5 sm:p-7" aria-labelledby="admin-recipe-instructions-heading">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-vegan-leaf-dark">Zubereitung</p>
            <h2 id="admin-recipe-instructions-heading" class="mt-1 text-2xl font-bold text-vegan-ink">So wird es gemacht</h2>
            <div class="mt-5 whitespace-pre-line break-words text-base leading-8 text-[#455249]">{{ $recipe->instructions }}</div>
        </section>
    </div>
</x-app-shell>
