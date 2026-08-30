@props([
    'eyebrow' => null,
    'title',
    'description' => null,
])

<div class="min-h-screen bg-vegan-cream px-3 py-4 sm:px-6 sm:py-7">
    <div class="mx-auto max-w-7xl">
        <header class="flex flex-col gap-5 px-1 sm:px-2 lg:flex-row lg:items-center lg:justify-between">
            <a wire:navigate href="{{ route('dashboard') }}" class="flex w-fit items-center gap-3 rounded-xl focus:outline-none focus:ring-4 focus:ring-vegan-mist">
                <span class="flex size-11 items-center justify-center rounded-full bg-vegan-mist">
                    <x-icon-sprout class="h-8 w-8" />
                </span>
                <span>
                    <span class="block text-[0.65rem] font-semibold uppercase tracking-[0.24em] text-vegan-leaf-dark">Veganizer</span>
                    <span class="font-display text-2xl font-bold tracking-tight text-vegan-ink">Rezeptverwaltung</span>
                </span>
            </a>

            <div class="flex flex-wrap items-center gap-2">
                <nav aria-label="Hauptnavigation" class="flex flex-wrap items-center gap-1 rounded-2xl bg-vegan-paper p-1 ring-1 ring-vegan-line/80">
                    <a
                        wire:navigate
                        href="{{ route('dashboard') }}"
                        @if (request()->routeIs('dashboard')) aria-current="page" @endif
                        class="min-h-11 rounded-xl px-4 py-2.5 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-vegan-leaf {{ request()->routeIs('dashboard') ? 'bg-vegan-mist text-vegan-ink' : 'text-[#536057] hover:bg-vegan-mist/60' }}"
                    >
                        Übersicht
                    </a>
                    <a
                        wire:navigate
                        href="{{ route('recipes.index') }}"
                        @if (request()->routeIs('recipes.index', 'recipes.show', 'recipes.edit')) aria-current="page" @endif
                        class="min-h-11 rounded-xl px-4 py-2.5 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-vegan-leaf {{ request()->routeIs('recipes.index', 'recipes.show', 'recipes.edit') ? 'bg-vegan-mist text-vegan-ink' : 'text-[#536057] hover:bg-vegan-mist/60' }}"
                    >
                        Meine Rezepte
                    </a>
                </nav>

                <a wire:navigate href="{{ route('recipes.create') }}" class="inline-flex min-h-11 items-center rounded-xl bg-vegan-leaf-dark px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#4f6623] focus:outline-none focus:ring-4 focus:ring-vegan-mist">
                    + Neues Rezept
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="min-h-11 rounded-xl border border-vegan-line bg-vegan-paper px-4 py-2.5 text-sm font-bold text-vegan-leaf-dark transition hover:bg-vegan-mist focus:outline-none focus:ring-4 focus:ring-vegan-mist">
                        Abmelden
                    </button>
                </form>
            </div>
        </header>

        <main class="relative mt-6 overflow-hidden rounded-[2rem] border border-[#aab58a] bg-vegan-paper shadow-[0_24px_70px_rgba(50,67,32,0.10)]">
            <div aria-hidden="true" class="absolute -right-28 top-14 size-72 rounded-full bg-vegan-mist/75"></div>

            <div class="relative z-10 p-5 sm:p-8 lg:p-10">
                <section class="flex flex-col gap-5 border-b border-vegan-line pb-7 sm:flex-row sm:items-end sm:justify-between">
                    <div class="max-w-3xl">
                        @if ($eyebrow)
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-vegan-leaf-dark">{{ $eyebrow }}</p>
                        @endif
                        <h1 class="mt-2 text-3xl font-bold tracking-[-0.025em] text-vegan-ink sm:text-4xl">{{ $title }}</h1>
                        @if ($description)
                            <p class="mt-3 leading-relaxed text-[#5b675e]">{{ $description }}</p>
                        @endif
                    </div>

                    @isset($actions)
                        <div class="shrink-0">{{ $actions }}</div>
                    @endisset
                </section>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-[#b9cb8c] bg-vegan-mist/70 px-5 py-4 font-semibold text-vegan-ink" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="mt-7">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</div>
