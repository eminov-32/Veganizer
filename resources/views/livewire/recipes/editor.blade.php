<x-app-shell
    eyebrow="Rezepteditor"
    :title="$isEditing ? 'Rezept bearbeiten' : 'Neues Rezept eingeben'"
    :description="$isEditing ? 'Passe Zutaten oder Zubereitung an und speichere deine Änderungen.' : 'Erfasse dein Rezept. Es bleibt zunächst privat und nur für dich sichtbar.'"
>
    <div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(300px,0.65fr)]">
        <form wire:submit="save" class="rounded-2xl border border-vegan-line bg-[#fffefa] p-5 sm:p-7" novalidate>
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800" role="alert">
                    <p class="font-bold">Bitte überprüfe deine Angaben.</p>
                    <p class="mt-1 text-sm">Mindestens ein Feld ist noch nicht vollständig ausgefüllt.</p>
                </div>
            @endif

            <section aria-labelledby="recipe-basics-heading">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-vegan-leaf-dark">Grunddaten</p>
                    <h2 id="recipe-basics-heading" class="mt-1 text-xl font-bold text-vegan-ink">Was möchtest du speichern?</h2>
                </div>

                <div class="mt-5 space-y-5">
                    <div>
                        <label for="title" class="mb-2 block text-sm font-bold text-vegan-ink">Rezeptname <span aria-hidden="true">*</span></label>
                        <input
                            wire:model="title"
                            id="title"
                            type="text"
                            maxlength="160"
                            placeholder="z. B. Cremige vegane Carbonara"
                            autocomplete="off"
                            aria-invalid="{{ $errors->has('title') ? 'true' : 'false' }}"
                            @error('title') aria-describedby="title-error" @enderror
                            class="min-h-12 w-full rounded-xl border bg-white px-4 py-3 text-vegan-ink outline-none transition placeholder:text-[#8a918a] focus:border-vegan-leaf-dark focus:ring-4 focus:ring-vegan-mist @error('title') border-red-400 @else border-vegan-line @enderror"
                        >
                        @error('title')
                            <p id="title-error" class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="mb-2 block text-sm font-bold text-vegan-ink">Kurze Beschreibung <span class="font-normal text-[#748077]">(optional)</span></label>
                        <textarea
                            wire:model="description"
                            id="description"
                            rows="3"
                            maxlength="1000"
                            placeholder="Was macht dieses Rezept besonders?"
                            aria-invalid="{{ $errors->has('description') ? 'true' : 'false' }}"
                            class="w-full rounded-xl border border-vegan-line bg-white px-4 py-3 text-vegan-ink outline-none transition placeholder:text-[#8a918a] focus:border-vegan-leaf-dark focus:ring-4 focus:ring-vegan-mist"
                        ></textarea>
                        @error('description')
                            <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label for="servings" class="mb-2 block text-sm font-bold text-vegan-ink">Portionen</label>
                            <input wire:model="servings" id="servings" type="number" min="1" max="100" inputmode="numeric" placeholder="4" class="min-h-12 w-full rounded-xl border border-vegan-line bg-white px-4 py-3 text-vegan-ink outline-none focus:border-vegan-leaf-dark focus:ring-4 focus:ring-vegan-mist">
                            @error('servings') <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="prepMinutes" class="mb-2 block text-sm font-bold text-vegan-ink">Vorbereitung</label>
                            <div class="relative">
                                <input wire:model="prepMinutes" id="prepMinutes" type="number" min="0" max="1440" inputmode="numeric" placeholder="15" class="min-h-12 w-full rounded-xl border border-vegan-line bg-white px-4 py-3 pr-12 text-vegan-ink outline-none focus:border-vegan-leaf-dark focus:ring-4 focus:ring-vegan-mist">
                                <span class="pointer-events-none absolute right-4 top-3.5 text-sm text-[#748077]">Min.</span>
                            </div>
                            @error('prepMinutes') <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="cookMinutes" class="mb-2 block text-sm font-bold text-vegan-ink">Kochzeit</label>
                            <div class="relative">
                                <input wire:model="cookMinutes" id="cookMinutes" type="number" min="0" max="1440" inputmode="numeric" placeholder="20" class="min-h-12 w-full rounded-xl border border-vegan-line bg-white px-4 py-3 pr-12 text-vegan-ink outline-none focus:border-vegan-leaf-dark focus:ring-4 focus:ring-vegan-mist">
                                <span class="pointer-events-none absolute right-4 top-3.5 text-sm text-[#748077]">Min.</span>
                            </div>
                            @error('cookMinutes') <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-9 border-t border-vegan-line pt-7" aria-labelledby="ingredients-heading">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-vegan-leaf-dark">Zutaten</p>
                        <h2 id="ingredients-heading" class="mt-1 text-xl font-bold text-vegan-ink">Was gehört hinein?</h2>
                    </div>
                    <button type="button" wire:click="addIngredient" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-vegan-line bg-vegan-paper px-4 py-2 text-sm font-bold text-vegan-leaf-dark transition hover:bg-vegan-mist focus:outline-none focus:ring-4 focus:ring-vegan-mist">
                        + Zutat hinzufügen
                    </button>
                </div>

                <div class="mt-5 space-y-4">
                    @foreach ($ingredients as $index => $ingredient)
                        <fieldset wire:key="ingredient-{{ $ingredient['key'] }}" class="rounded-2xl border border-vegan-line bg-[#fbfcf4] p-4">
                            <legend class="px-2 text-sm font-bold text-vegan-ink">Zutat {{ $index + 1 }}</legend>
                            <div class="grid gap-3 sm:grid-cols-[0.75fr_0.8fr_1.7fr_auto] sm:items-end">
                                <div>
                                    <label for="ingredient-{{ $ingredient['key'] }}-amount" class="mb-1.5 block text-xs font-bold text-[#566259]">Menge</label>
                                    <input wire:model="ingredients.{{ $index }}.amount" id="ingredient-{{ $ingredient['key'] }}-amount" type="text" maxlength="40" placeholder="250" class="min-h-11 w-full rounded-xl border border-vegan-line bg-white px-3 py-2 text-vegan-ink outline-none focus:border-vegan-leaf-dark focus:ring-4 focus:ring-vegan-mist">
                                </div>
                                <div>
                                    <label for="ingredient-{{ $ingredient['key'] }}-unit" class="mb-1.5 block text-xs font-bold text-[#566259]">Einheit</label>
                                    <input wire:model="ingredients.{{ $index }}.unit" id="ingredient-{{ $ingredient['key'] }}-unit" type="text" maxlength="40" placeholder="ml" class="min-h-11 w-full rounded-xl border border-vegan-line bg-white px-3 py-2 text-vegan-ink outline-none focus:border-vegan-leaf-dark focus:ring-4 focus:ring-vegan-mist">
                                </div>
                                <div>
                                    <label for="ingredient-{{ $ingredient['key'] }}-name" class="mb-1.5 block text-xs font-bold text-[#566259]">Zutat <span aria-hidden="true">*</span></label>
                                    <input
                                        wire:model="ingredients.{{ $index }}.name"
                                        id="ingredient-{{ $ingredient['key'] }}-name"
                                        type="text"
                                        maxlength="120"
                                        placeholder="Haferdrink"
                                        aria-invalid="{{ $errors->has('ingredients.'.$index.'.name') ? 'true' : 'false' }}"
                                        class="min-h-11 w-full rounded-xl border bg-white px-3 py-2 text-vegan-ink outline-none focus:border-vegan-leaf-dark focus:ring-4 focus:ring-vegan-mist @error('ingredients.'.$index.'.name') border-red-400 @else border-vegan-line @enderror"
                                    >
                                </div>
                                <button type="button" wire:click="removeIngredient({{ $index }})" aria-label="Zutat {{ $index + 1 }} entfernen" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-red-200 px-3 py-2 text-sm font-bold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-100">
                                    Entfernen
                                </button>
                            </div>
                            <div class="mt-3">
                                <label for="ingredient-{{ $ingredient['key'] }}-notes" class="sr-only">Hinweis zu Zutat {{ $index + 1 }}</label>
                                <input wire:model="ingredients.{{ $index }}.notes" id="ingredient-{{ $ingredient['key'] }}-notes" type="text" maxlength="255" placeholder="Optionaler Hinweis, z. B. fein gehackt" class="min-h-11 w-full rounded-xl border border-vegan-line bg-white px-3 py-2 text-sm text-vegan-ink outline-none focus:border-vegan-leaf-dark focus:ring-4 focus:ring-vegan-mist">
                            </div>
                            @error('ingredients.'.$index.'.name')
                                <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                            @enderror
                        </fieldset>
                    @endforeach
                </div>
            </section>

            <section class="mt-9 border-t border-vegan-line pt-7" aria-labelledby="instructions-heading">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-vegan-leaf-dark">Zubereitung</p>
                <h2 id="instructions-heading" class="mt-1 text-xl font-bold text-vegan-ink">Wie wird das Rezept zubereitet?</h2>
                <label for="instructions" class="sr-only">Zubereitung</label>
                <textarea
                    wire:model="instructions"
                    id="instructions"
                    rows="8"
                    maxlength="20000"
                    placeholder="Beschreibe die einzelnen Schritte …"
                    aria-invalid="{{ $errors->has('instructions') ? 'true' : 'false' }}"
                    @error('instructions') aria-describedby="instructions-error" @enderror
                    class="mt-4 w-full rounded-xl border bg-white px-4 py-3 leading-relaxed text-vegan-ink outline-none transition placeholder:text-[#8a918a] focus:border-vegan-leaf-dark focus:ring-4 focus:ring-vegan-mist @error('instructions') border-red-400 @else border-vegan-line @enderror"
                ></textarea>
                @error('instructions')
                    <p id="instructions-error" class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                @enderror
            </section>

            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-vegan-line pt-6 sm:flex-row sm:justify-end">
                <a wire:navigate href="{{ $cancelUrl }}" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-vegan-line px-5 py-3 font-bold text-vegan-leaf-dark transition hover:bg-vegan-mist focus:outline-none focus:ring-4 focus:ring-vegan-mist">Abbrechen</a>
                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-vegan-leaf-dark px-6 py-3 font-bold text-white transition hover:bg-[#4f6623] focus:outline-none focus:ring-4 focus:ring-vegan-mist disabled:cursor-wait disabled:opacity-70">
                    <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Änderungen speichern' : 'Rezept speichern' }}</span>
                    <span wire:loading wire:target="save">Wird gespeichert …</span>
                </button>
            </div>
        </form>

        <aside class="rounded-2xl border border-vegan-line bg-[#fbfcf4] p-5 xl:sticky xl:top-6" aria-labelledby="preview-heading">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-vegan-leaf-dark">Vorschau</p>
            <h2 id="preview-heading" class="mt-2 text-2xl font-bold text-vegan-ink">{{ $title ?: 'Dein neues Rezept' }}</h2>
            <p class="mt-3 text-sm leading-relaxed text-[#606b62]">{{ $description ?: 'Deine Beschreibung erscheint später an dieser Stelle.' }}</p>

            <div class="mt-5 flex flex-wrap gap-2 text-xs font-semibold text-vegan-leaf-dark">
                @if ($servings) <span class="rounded-full bg-vegan-mist px-3 py-1.5">{{ $servings }} Portionen</span> @endif
                @if ($prepMinutes) <span class="rounded-full bg-vegan-mist px-3 py-1.5">{{ $prepMinutes }} Min. Vorbereitung</span> @endif
                @if ($cookMinutes) <span class="rounded-full bg-vegan-mist px-3 py-1.5">{{ $cookMinutes }} Min. Kochzeit</span> @endif
            </div>

            <div class="mt-6 border-t border-vegan-line pt-5">
                <h3 class="font-bold text-vegan-ink">Zutaten</h3>
                <ul class="mt-3 space-y-2 text-sm text-[#5b675e]">
                    @foreach ($ingredients as $ingredient)
                        <li wire:key="preview-{{ $ingredient['key'] }}" class="flex gap-2">
                            <span aria-hidden="true" class="mt-2 size-1.5 shrink-0 rounded-full bg-vegan-leaf"></span>
                            <span>
                                {{ trim(($ingredient['amount'] ?? '').' '.($ingredient['unit'] ?? '')) ?: '–' }}
                                {{ $ingredient['name'] ?: 'Zutat' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <p class="mt-6 rounded-xl bg-vegan-mist/70 p-4 text-sm leading-relaxed text-[#516047]">
                Später kann Veganizer hier nicht-vegane Zutaten erkennen und passende Alternativen vorschlagen.
            </p>
        </aside>
    </div>
</x-app-shell>
