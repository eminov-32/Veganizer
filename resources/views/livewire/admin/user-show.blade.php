<x-app-shell
    eyebrow="Admin · Benutzerprofil"
    :title="$user->name"
    description="Kontoübersicht und gespeicherte Rezepte dieses Benutzers."
>
    <x-slot:actions>
        <span class="inline-flex min-h-11 items-center rounded-full bg-vegan-mist px-4 py-2 text-sm font-bold text-vegan-ink">
            Nur ansehen
        </span>
    </x-slot:actions>

    <a wire:navigate href="{{ route('admin.users.index') }}" class="inline-flex min-h-11 items-center rounded-lg text-sm font-bold text-vegan-leaf-dark underline decoration-transparent underline-offset-4 hover:decoration-current focus:outline-none focus:ring-2 focus:ring-vegan-leaf">
        ← Alle Benutzerkonten
    </a>

    <section class="mt-5 rounded-2xl border border-vegan-line bg-[#fbfcf4] p-5 sm:p-7" aria-labelledby="account-details-heading">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex min-w-0 items-center gap-4">
                <span aria-hidden="true" class="flex size-16 shrink-0 items-center justify-center rounded-full bg-vegan-mist text-2xl font-bold uppercase text-vegan-ink">
                    {{ mb_substr($user->name, 0, 1) }}
                </span>
                <div class="min-w-0">
                    <h2 id="account-details-heading" class="break-words text-2xl font-bold text-vegan-ink">Kontodaten</h2>
                    <p class="mt-1 break-all text-[#5f6b62]">{{ $user->email }}</p>
                </div>
            </div>
            <span class="w-fit rounded-full border border-vegan-line bg-white px-3 py-1.5 text-xs font-bold text-vegan-leaf-dark">{{ $user->role->label() }}</span>
        </div>

        <dl class="mt-6 grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-vegan-line bg-white p-4">
                <dt class="text-sm text-[#69746c]">Registriert am</dt>
                <dd class="mt-1 font-bold text-vegan-ink"><time datetime="{{ $user->created_at->toDateString() }}">{{ $user->created_at->format('d.m.Y') }}</time></dd>
            </div>
            <div class="rounded-xl border border-vegan-line bg-white p-4">
                <dt class="text-sm text-[#69746c]">Gespeicherte Rezepte</dt>
                <dd class="mt-1 font-bold text-vegan-ink">{{ $user->recipes_count }}</dd>
            </div>
            <div class="rounded-xl border border-vegan-line bg-white p-4">
                <dt class="text-sm text-[#69746c]">Kontonummer</dt>
                <dd class="mt-1 font-bold text-vegan-ink">#{{ $user->id }}</dd>
            </div>
        </dl>
    </section>

    <section class="mt-8" aria-labelledby="user-recipes-heading">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-vegan-leaf-dark">Private Inhalte</p>
            <h2 id="user-recipes-heading" class="mt-1 text-2xl font-bold text-vegan-ink">Rezepte von {{ $user->name }}</h2>
            <p class="mt-2 text-sm text-[#637067]">Adminansicht – die Inhalte können hier nur angesehen werden.</p>
        </div>

        @if ($recipes->isEmpty())
            <div class="mt-5 rounded-2xl border border-dashed border-vegan-line bg-[#fbfcf4] px-6 py-12 text-center">
                <x-icon-sprout class="mx-auto h-14 w-14" />
                <h3 class="mt-4 text-xl font-bold text-vegan-ink">Noch keine Rezepte gespeichert</h3>
                <p class="mt-2 text-[#637067]">Dieses Konto hat bisher kein eigenes Rezept angelegt.</p>
            </div>
        @else
            <ul class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($recipes as $recipe)
                    <li class="flex min-w-0 flex-col rounded-2xl border border-vegan-line bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="break-words text-lg font-bold text-vegan-ink">{{ $recipe->title }}</h3>
                            <span class="shrink-0 rounded-full bg-vegan-mist px-2.5 py-1 text-xs font-bold text-vegan-ink">
                                {{ $recipe->published_at ? 'Veröffentlicht' : 'Privat' }}
                            </span>
                        </div>
                        <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-[#637067]">{{ $recipe->description ?: 'Keine Beschreibung hinterlegt.' }}</p>
                        <p class="mt-5 text-sm text-[#69746c]">{{ $recipe->ingredients_count }} {{ $recipe->ingredients_count === 1 ? 'Zutat' : 'Zutaten' }}</p>
                        <p class="mt-1 text-xs text-[#637067]">Zuletzt bearbeitet <time datetime="{{ $recipe->updated_at->toIso8601String() }}">{{ $recipe->updated_at->diffForHumans() }}</time></p>

                        <a
                            wire:navigate
                            href="{{ route('admin.users.recipes.show', ['userId' => $user->id, 'recipeId' => $recipe->id]) }}"
                            aria-label="Rezept {{ $recipe->title }} von {{ $user->name }} ansehen"
                            class="mt-5 inline-flex min-h-11 items-center justify-center rounded-xl border border-vegan-line px-4 py-2.5 text-sm font-bold text-vegan-leaf-dark transition hover:bg-vegan-mist focus:outline-none focus:ring-4 focus:ring-vegan-mist"
                        >
                            Rezept ansehen
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6">{{ $recipes->links() }}</div>
        @endif
    </section>
</x-app-shell>
