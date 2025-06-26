<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-extrabold text-emerald-700 mb-8 text-center">📂 Deine Dokumente</h2>

        @if ($documents->isEmpty())
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl p-6 text-center shadow-md">
                <p class="text-lg font-medium">Noch keine Dokumente vorhanden.</p>
                <p class="text-sm mt-2">Lade jetzt dein erstes PDF hoch und beginne mit dem Unterschreiben!</p>
                <a href="{{ route('documents.create') }}"
                    class="mt-4 inline-block bg-emerald-600 text-white px-5 py-2 rounded-full hover:bg-emerald-700 transition">
                    📤 Dokument hochladen
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($documents as $document)
                    <a href="{{ route('documents.show', $document) }}"
                       class="group bg-gradient-to-br from-emerald-50 via-white to-lime-50 border border-emerald-100 rounded-2xl p-6 shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <div class="flex flex-col h-full justify-between">
                            <div class="mb-4">
                                <div class="text-3xl mb-2 group-hover:scale-110 transition">📄</div>
                                <h3 class="text-lg font-semibold text-emerald-800 mb-1 truncate">{{ $document->title }}</h3>
                                <p class="text-sm text-gray-600">Hochgeladen am<br><span class="font-medium text-gray-800">{{ $document->created_at->format('d.m.Y H:i') }}</span></p>
                            </div>
                            <div class="text-sm text-lime-700 font-medium group-hover:underline">➔ ansehen & unterschreiben</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
