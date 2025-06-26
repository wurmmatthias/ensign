<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Du hast dich erfolgreich angemeldet.") }}
                </div>
            </div>
        </div>

            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-extrabold text-emerald-700 mb-10 text-center">📚 Willkommen im Dokumenten-Dashboard</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Karte 1: Dokument hochladen -->
            <a href="{{ route('documents.create') }}"
                class="group bg-gradient-to-br from-emerald-100 via-white to-lime-100 border border-emerald-200 rounded-2xl p-8 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex flex-col items-center text-center">
                    <div class="text-4xl mb-4 group-hover:scale-110 transition">📤</div>
                    <h3 class="text-xl font-bold text-emerald-800 mb-2">Dokument hochladen</h3>
                    <p class="text-gray-600 text-sm">Lade neue PDFs hoch, die unterschrieben oder geteilt werden sollen.</p>
                </div>
            </a>

            <!-- Karte 2: Dokumente ansehen -->
            <a href="{{ route('documents.index') }}"
                class="group bg-gradient-to-br from-lime-100 via-white to-emerald-100 border border-lime-200 rounded-2xl p-8 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex flex-col items-center text-center">
                    <div class="text-4xl mb-4 group-hover:scale-110 transition">📂</div>
                    <h3 class="text-xl font-bold text-emerald-800 mb-2">Dokumente ansehen</h3>
                    <p class="text-gray-600 text-sm">Durchsuche, filtere und verwalte bereits hochgeladene Dokumente.</p>
                </div>
            </a>

            <!-- Karte 3: Unterschriftslauf erstellen -->
            <a href="#"
                class="group bg-gradient-to-br from-emerald-50 via-white to-lime-50 border border-emerald-100 rounded-2xl p-8 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex flex-col items-center text-center">
                    <div class="text-4xl mb-4 group-hover:scale-110 transition">✍️</div>
                    <h3 class="text-xl font-bold text-emerald-800 mb-2">Unterschriftslauf erstellen</h3>
                    <p class="text-gray-600 text-sm">Starte einen Signaturprozess für ein oder mehrere Empfänger.</p>
                </div>
            </a>
        </div>
    </div>
    </div>
</x-app-layout>
