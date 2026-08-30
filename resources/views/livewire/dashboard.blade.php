<main class="min-h-screen bg-vegan-cream p-4 sm:p-8">
    <div class="mx-auto max-w-5xl rounded-[2rem] border border-vegan-line bg-vegan-paper p-7 shadow-[0_20px_60px_rgba(50,67,32,0.10)] sm:p-10">
        <header class="flex flex-col gap-5 border-b border-vegan-line pb-7 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <x-icon-sprout class="h-12 w-12" />
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-vegan-leaf-dark">Veganizer</p>
                    <h1 class="mt-1 text-2xl font-bold text-vegan-ink">Hallo {{ auth()->user()->name }} 👋</h1>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded-xl border border-vegan-line px-5 py-2.5 text-sm font-bold text-vegan-leaf-dark transition hover:bg-vegan-mist focus:outline-none focus:ring-4 focus:ring-vegan-mist">
                    Abmelden
                </button>
            </form>
        </header>

        <section class="py-12 text-center sm:py-16">
            <div class="mx-auto flex size-20 items-center justify-center rounded-full bg-vegan-mist">
                <x-icon-sprout class="h-14 w-14" />
            </div>
            <h2 class="mt-6 text-3xl font-bold tracking-tight text-vegan-ink">Dein Konto ist bereit</h2>
            <p class="mx-auto mt-3 max-w-xl leading-relaxed text-[#5a665d]">
                Anmeldung und Registrierung funktionieren. Im nächsten Schritt entsteht hier deine Rezept-Startseite.
            </p>
        </section>
    </div>
</main>
