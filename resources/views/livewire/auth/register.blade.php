<x-auth-layout
    heading="Registrierung"
    description="Erstelle dein kostenloses Konto und speichere deine veganisierten Lieblingsrezepte."
>
    <form wire:submit="register" class="space-y-4" novalidate>
        <div>
            <label for="name" class="mb-2 block text-sm font-semibold text-vegan-ink sm:text-base">Name</label>
            <input
                wire:model="name"
                id="name"
                name="name"
                type="text"
                autocomplete="name"
                placeholder="z. B. Lisa"
                autofocus
                class="w-full rounded-2xl border bg-white px-4 py-3 text-base text-vegan-ink outline-none transition placeholder:text-[#8a918a] focus:border-vegan-leaf focus:ring-4 focus:ring-vegan-mist/80 @error('name') border-red-400 @else border-[#d5dac3] @enderror"
            >
            @error('name')
                <p class="mt-2 text-sm font-medium text-red-700" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-vegan-ink sm:text-base">E-Mail</label>
            <input
                wire:model="email"
                id="email"
                name="email"
                type="email"
                autocomplete="email"
                placeholder="deine@email.de"
                class="w-full rounded-2xl border bg-white px-4 py-3 text-base text-vegan-ink outline-none transition placeholder:text-[#8a918a] focus:border-vegan-leaf focus:ring-4 focus:ring-vegan-mist/80 @error('email') border-red-400 @else border-[#d5dac3] @enderror"
            >
            @error('email')
                <p class="mt-2 text-sm font-medium text-red-700" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-vegan-ink sm:text-base">Passwort</label>
                <input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Mindestens 8 Zeichen"
                    class="w-full rounded-2xl border bg-white px-4 py-3 text-base text-vegan-ink outline-none transition placeholder:text-[#8a918a] focus:border-vegan-leaf focus:ring-4 focus:ring-vegan-mist/80 @error('password') border-red-400 @else border-[#d5dac3] @enderror"
                >
                @error('password')
                    <p class="mt-2 text-sm font-medium text-red-700" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-vegan-ink sm:text-base">Bestätigen</label>
                <input
                    wire:model="password_confirmation"
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Passwort wiederholen"
                    class="w-full rounded-2xl border border-[#d5dac3] bg-white px-4 py-3 text-base text-vegan-ink outline-none transition placeholder:text-[#8a918a] focus:border-vegan-leaf focus:ring-4 focus:ring-vegan-mist/80"
                >
            </div>
        </div>

        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="register"
            class="inline-flex min-h-12 w-full items-center justify-center rounded-2xl bg-vegan-leaf px-6 py-3 font-bold text-white shadow-[0_8px_22px_rgba(95,121,42,0.22)] transition hover:bg-vegan-leaf-dark focus:outline-none focus:ring-4 focus:ring-vegan-mist disabled:cursor-wait disabled:opacity-70"
        >
            <span wire:loading.remove wire:target="register">Konto erstellen</span>
            <span wire:loading wire:target="register">Konto wird erstellt …</span>
        </button>

        <p class="pt-1 text-center text-sm text-[#526056] sm:text-base">
            Du hast bereits einen Account?
            <a wire:navigate href="{{ route('login') }}" class="font-bold text-vegan-leaf-dark underline decoration-transparent underline-offset-4 transition hover:decoration-current focus:outline-none focus:ring-2 focus:ring-vegan-leaf">
                Anmelden
            </a>
        </p>
    </form>
</x-auth-layout>
