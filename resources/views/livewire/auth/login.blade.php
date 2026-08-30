<x-auth-layout
    heading="Anmeldung"
    description="Melde dich an und starte direkt mit deiner veganen Rezeptsammlung."
>
    <form wire:submit="login" class="space-y-5" novalidate>
        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-vegan-ink sm:text-base">E-Mail</label>
            <input
                wire:model="email"
                id="email"
                name="email"
                type="email"
                autocomplete="email"
                placeholder="deine@email.de"
                autofocus
                class="w-full rounded-2xl border bg-white px-4 py-3.5 text-base text-vegan-ink outline-none transition placeholder:text-[#8a918a] focus:border-vegan-leaf focus:ring-4 focus:ring-vegan-mist/80 @error('email') border-red-400 @else border-[#d5dac3] @enderror"
            >
            @error('email')
                <p class="mt-2 text-sm font-medium text-red-700" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-semibold text-vegan-ink sm:text-base">Passwort</label>
            <input
                wire:model="password"
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                placeholder="Dein Passwort"
                class="w-full rounded-2xl border bg-white px-4 py-3.5 text-base text-vegan-ink outline-none transition placeholder:text-[#8a918a] focus:border-vegan-leaf focus:ring-4 focus:ring-vegan-mist/80 @error('password') border-red-400 @else border-[#d5dac3] @enderror"
            >
            @error('password')
                <p class="mt-2 text-sm font-medium text-red-700" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex w-fit cursor-pointer items-center gap-3 text-sm text-[#526056]">
            <input wire:model="remember" type="checkbox" class="size-4 rounded border-[#bfc7a7] text-vegan-leaf focus:ring-vegan-leaf">
            Angemeldet bleiben
        </label>

        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="login"
            class="inline-flex min-h-12 w-full items-center justify-center rounded-2xl bg-vegan-leaf px-6 py-3 font-bold text-white shadow-[0_8px_22px_rgba(95,121,42,0.22)] transition hover:bg-vegan-leaf-dark focus:outline-none focus:ring-4 focus:ring-vegan-mist disabled:cursor-wait disabled:opacity-70 sm:w-auto sm:min-w-40"
        >
            <span wire:loading.remove wire:target="login">Einloggen</span>
            <span wire:loading wire:target="login">Wird angemeldet …</span>
        </button>

        <div class="flex items-center gap-4 pt-1 text-sm text-[#7a8277]" aria-hidden="true">
            <span class="h-px flex-1 bg-[#d8dcc9]"></span>
            <span>oder</span>
            <span class="h-px flex-1 bg-[#d8dcc9]"></span>
        </div>

        <p class="text-center text-sm text-[#526056] sm:text-base">
            Noch keinen Account?
            <a wire:navigate href="{{ route('register') }}" class="font-bold text-vegan-leaf-dark underline decoration-transparent underline-offset-4 transition hover:decoration-current focus:outline-none focus:ring-2 focus:ring-vegan-leaf">
                Registrieren
            </a>
        </p>
    </form>
</x-auth-layout>
