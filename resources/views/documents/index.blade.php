<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-emerald-500 via-emerald-600 to-lime-500 text-white py-16 shadow-lg relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h1 class="text-4xl font-extrabold mb-4 drop-shadow-lg">📂 Deine digitalen Dokumente</h1>
            <p class="text-lg text-emerald-100 mb-6">Verwalte, teile und unterschreibe PDF-Dateien sicher & komfortabel.</p>
            <a href="{{ route('documents.create') }}"
               class="inline-block bg-white text-emerald-700 font-semibold px-6 py-3 rounded-full hover:bg-emerald-50 transition shadow-lg">
                📤 Dokument hochladen
            </a>
        </div>
    </div>
    <br>
    <br>

    <!-- Document Grid Section -->
    <div class="max-w-7xl mx-auto px-6 py-12 -mt-12">
        @if ($documents->isEmpty())
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl p-8 text-center shadow-md">
                <p class="text-lg font-semibold">Noch keine Dokumente vorhanden.</p>
                <p class="text-sm mt-2">Lade jetzt dein erstes PDF hoch und beginne mit dem Unterschreiben.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($documents as $document)
                    <div class="bg-white border border-gray-200 rounded-2xl shadow hover:shadow-lg transition-all duration-300 p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <div class="text-3xl">📄</div>
                                <h3 class="text-lg font-semibold text-emerald-800 truncate">
                                    {{ $document->title }}
                                </h3>
                            </div>
                            <p class="text-sm text-gray-600">
                                Hochgeladen am:<br>
                                <span class="font-medium text-gray-800">
                                    {{ $document->created_at->format('d.m.Y H:i') }}
                                </span>
                            </p>
                        </div>

                        <div class="mt-5 space-y-2">
                            <a href="{{ route('documents.show', $document) }}"
                               class="text-sm text-emerald-700 font-medium hover:underline inline-flex items-center gap-1">
                                ➔ Ansehen & unterschreiben
                            </a>

                            <form method="POST" action="{{ route('documents.share', $document) }}">
                                @csrf
                                <button type="submit"
                                    class="text-sm text-emerald-600 hover:underline inline-flex items-center gap-1">
                                    🔗 Öffentlich teilen
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Sharing Modal -->
    @if(session('share_link'))
        <div id="share-modal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white max-w-md w-full mx-4 p-6 rounded-xl shadow-lg relative">
                <h3 class="text-lg font-semibold text-emerald-700 mb-3">
                    🔗 Öffentlicher Link erstellt
                </h3>

                <p class="text-sm text-gray-600 mb-1">
                    Der folgende Link ist öffentlich aufrufbar:
                </p>

                <div class="bg-gray-100 border border-gray-300 rounded px-3 py-2 text-sm text-gray-800 overflow-x-auto mb-3">
                    <a href="{{ session('share_link') }}" target="_blank" class="underline">
                        {{ session('share_link') }}
                    </a>
                </div>

                @if(session('share_expires_at'))
                    <p class="text-xs text-gray-500">
                        🕒 Gültig bis:
                        <span class="font-medium text-gray-700">
                            {{ \Carbon\Carbon::parse(session('share_expires_at'))->format('d.m.Y H:i') }}
                        </span>
                    </p>
                @endif

                <div class="mt-5 flex justify-end">
                    <button onclick="document.getElementById('share-modal').remove();"
                            class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 transition">
                        Schließen
                    </button>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
