@props([
    'heading',
    'description',
])

<div class="relative min-h-screen overflow-hidden bg-vegan-cream p-3 sm:p-5 lg:p-7">
    <div aria-hidden="true" class="absolute -bottom-28 -left-24 size-72 rounded-full bg-vegan-mist sm:size-96"></div>
    <div aria-hidden="true" class="absolute -right-32 top-28 size-80 rounded-full bg-vegan-mist/80 lg:size-[30rem]"></div>

    <main class="relative mx-auto grid min-h-[calc(100vh-1.5rem)] max-w-[1520px] overflow-hidden rounded-[1.75rem] border border-[#9daa78] bg-[#fbf9f1]/90 shadow-[0_22px_70px_rgba(50,67,32,0.12)] sm:min-h-[calc(100vh-2.5rem)] lg:grid-cols-[minmax(0,1.08fr)_minmax(420px,0.92fr)]">
        <section class="relative flex min-h-[19rem] flex-col justify-center px-7 pb-10 pt-20 sm:px-12 lg:min-h-full lg:px-[clamp(3rem,6vw,7rem)] lg:py-24">
            <div aria-hidden="true" class="absolute bottom-8 right-8 hidden lg:block">
                <x-icon-sprout class="h-32 w-32 opacity-90" />
            </div>

            <div class="relative z-10 max-w-3xl">
                <div class="flex items-end gap-3 sm:gap-5">
                    <h2 class="font-display text-5xl font-bold leading-none tracking-[-0.045em] text-vegan-ink sm:text-7xl lg:text-[clamp(5rem,7vw,7.5rem)]">
                        Veganizer
                    </h2>
                    <x-icon-sprout class="mb-1 h-14 w-14 sm:h-20 sm:w-20 lg:h-24 lg:w-24" />
                </div>

                <p class="mt-7 max-w-2xl text-lg leading-relaxed text-[#344b39] sm:text-xl lg:text-2xl">
                    Wandle deine Lieblingsrezepte in vegane Alternativen um und speichere deine Kreationen.
                </p>

                <ul class="mt-8 hidden space-y-4 text-base text-[#29432f] sm:block lg:mt-12 lg:text-lg">
                    <li class="flex items-center gap-3">
                        <span class="size-2 rounded-full bg-[#91bc4c]"></span>
                        Klare Anmeldung und persönliche Startseite
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="size-2 rounded-full bg-[#91bc4c]"></span>
                        Übersichtliche Rezeptumwandlung
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="size-2 rounded-full bg-[#91bc4c]"></span>
                        Deine veganen Rezepte an einem Ort
                    </li>
                </ul>
            </div>
        </section>

        <section class="relative flex items-center px-4 pb-5 sm:px-8 sm:pb-8 lg:px-10 lg:py-14">
            <div aria-hidden="true" class="absolute -right-20 top-12 size-64 rounded-full bg-vegan-mist/70"></div>

            <div class="relative z-10 mx-auto w-full max-w-xl rounded-[1.75rem] border border-[#d5dac3] bg-[#fffdf8]/95 p-6 shadow-[0_18px_50px_rgba(57,75,39,0.10)] backdrop-blur-sm sm:p-9 lg:p-11">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-vegan-leaf-dark sm:text-sm">
                    Willkommen bei Veganizer
                </p>
                <h1 class="mt-5 text-4xl font-bold tracking-[-0.035em] text-vegan-ink sm:text-5xl">
                    {{ $heading }}
                </h1>
                <p class="mt-5 text-base leading-relaxed text-[#546259] sm:text-lg">
                    {{ $description }}
                </p>

                <div class="mt-8">
                    {{ $slot }}
                </div>
            </div>
        </section>
    </main>
</div>
