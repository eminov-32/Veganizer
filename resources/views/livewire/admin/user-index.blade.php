<x-app-shell
    eyebrow="Administration"
    title="Benutzerkonten"
    description="Durchsuche registrierte Konten und öffne ihre gespeicherten Rezepte."
>
    <x-slot:actions>
        <span class="inline-flex min-h-11 items-center rounded-full bg-vegan-mist px-4 py-2 text-sm font-bold text-vegan-ink">
            Nur für Admins
        </span>
    </x-slot:actions>

    <section aria-labelledby="user-search-heading">
        <h2 id="user-search-heading" class="text-xl font-bold text-vegan-ink">Benutzer durchsuchen</h2>

        <div class="mt-4 rounded-2xl border border-vegan-line bg-[#fbfcf4] p-4 sm:p-5" role="search">
            <label for="admin-user-search" class="block text-sm font-bold text-vegan-ink">Name oder E-Mail-Adresse</label>
            <div class="mt-2 flex flex-col gap-3 sm:flex-row">
                <input
                    id="admin-user-search"
                    type="search"
                    wire:model.live.debounce.350ms="search"
                    aria-controls="admin-user-results"
                    autocomplete="off"
                    placeholder="z. B. Lisa oder lisa@example.de"
                    class="min-h-12 min-w-0 flex-1 rounded-xl border border-vegan-line bg-white px-4 py-3 text-vegan-ink outline-none transition placeholder:text-[#69746c] focus:border-vegan-leaf focus:ring-4 focus:ring-vegan-mist"
                >

                @if ($searchTerm !== '')
                    <button
                        type="button"
                        wire:click="clearSearch"
                        class="min-h-12 rounded-xl border border-vegan-line bg-white px-5 py-3 text-sm font-bold text-vegan-leaf-dark transition hover:bg-vegan-mist focus:outline-none focus:ring-4 focus:ring-vegan-mist"
                    >
                        Suche löschen
                    </button>
                @endif
            </div>

            <p wire:loading.delay wire:target="search" class="mt-3 text-sm text-[#647067]" role="status">Suche wird aktualisiert …</p>
        </div>
    </section>

    <section id="admin-user-results" class="mt-7" aria-labelledby="user-results-heading">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-vegan-leaf-dark">Ergebnisse</p>
                <h2 id="user-results-heading" class="mt-1 text-2xl font-bold text-vegan-ink">Registrierte Konten</h2>
            </div>
            <p class="text-sm font-semibold text-[#5f6b62]" aria-live="polite" aria-atomic="true">
                {{ $users->total() }} {{ $users->total() === 1 ? 'Konto' : 'Konten' }}
            </p>
        </div>

        @if ($users->isEmpty())
            <div class="mt-5 rounded-2xl border border-dashed border-vegan-line bg-[#fbfcf4] px-6 py-12 text-center">
                <x-icon-sprout class="mx-auto h-14 w-14" />
                @if ($searchTerm !== '')
                    <h3 class="mt-4 text-xl font-bold text-vegan-ink">Keine passenden Konten gefunden</h3>
                    <p class="mt-2 text-[#637067]">Probiere einen anderen Namen oder lösche die Suche.</p>
                    <button type="button" wire:click="clearSearch" class="mt-5 min-h-11 rounded-xl bg-vegan-leaf-dark px-5 py-2.5 text-sm font-bold text-white focus:outline-none focus:ring-4 focus:ring-vegan-mist">
                        Alle Konten anzeigen
                    </button>
                @else
                    <h3 class="mt-4 text-xl font-bold text-vegan-ink">Noch keine Konten vorhanden</h3>
                    <p class="mt-2 text-[#637067]">Sobald sich jemand registriert, erscheint das Konto hier.</p>
                @endif
            </div>
        @else
            <ul class="mt-5 space-y-3">
                @foreach ($users as $user)
                    <li class="rounded-2xl border border-vegan-line bg-white p-4 shadow-sm sm:p-5">
                        <div class="grid items-center gap-4 md:grid-cols-[minmax(0,1fr)_auto_auto]">
                            <div class="flex min-w-0 items-center gap-4">
                                <span aria-hidden="true" class="flex size-12 shrink-0 items-center justify-center rounded-full bg-vegan-mist text-lg font-bold uppercase text-vegan-ink">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </span>
                                <div class="min-w-0">
                                    <h3 class="break-words text-lg font-bold text-vegan-ink">{{ $user->name }}</h3>
                                    <p class="break-all text-sm text-[#637067]">{{ $user->email }}</p>
                                </div>
                            </div>

                            <dl class="grid grid-cols-2 gap-4 text-sm md:min-w-64">
                                <div>
                                    <dt class="text-[#6a756d]">Registriert</dt>
                                    <dd class="mt-1 font-semibold text-vegan-ink">
                                        <time datetime="{{ $user->created_at->toDateString() }}">{{ $user->created_at->format('d.m.Y') }}</time>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[#6a756d]">Rezepte</dt>
                                    <dd class="mt-1 font-semibold text-vegan-ink">{{ $user->recipes_count }}</dd>
                                </div>
                            </dl>

                            <a
                                wire:navigate
                                href="{{ route('admin.users.show', ['userId' => $user->id]) }}"
                                aria-label="Profil von {{ $user->name }} ansehen"
                                class="inline-flex min-h-11 items-center justify-center rounded-xl border border-vegan-line px-4 py-2.5 text-sm font-bold text-vegan-leaf-dark transition hover:bg-vegan-mist focus:outline-none focus:ring-4 focus:ring-vegan-mist"
                            >
                                Profil ansehen
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6">{{ $users->links() }}</div>
        @endif
    </section>
</x-app-shell>
