<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-gradient-to-br from-emerald-50 via-white to-lime-50 border border-emerald-200 shadow-lg rounded-2xl p-8">
            <h2 class="text-3xl font-bold text-emerald-700 mb-6 flex items-center gap-2">
                📝 Dokument hochladen
            </h2>

            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Dokumenttitel -->
                <div>
                    <label for="title" class="block text-sm font-medium text-emerald-700 mb-1">📌 Titel des Dokuments</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        required 
                        class="w-full border border-emerald-300 rounded-xl px-4 py-2 text-gray-800 shadow-sm focus:outline-none focus:ring-4 focus:ring-lime-300 focus:border-lime-500 transition"
                        placeholder="z. B. Vertrag, Einwilligung …"
                    >
                </div>

                <!-- Datei-Upload -->
                <div>
                    <label for="document" class="block text-sm font-medium text-emerald-700 mb-1">📎 PDF-Datei auswählen</label>
                    <input 
                        type="file" 
                        name="document" 
                        id="document" 
                        accept="application/pdf" 
                        required
                        class="block w-full text-sm text-emerald-800 border border-emerald-300 rounded-xl cursor-pointer focus:outline-none focus:ring-4 focus:ring-lime-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 transition"
                    >
                </div>

                <!-- Fancy Call-to-Action -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-lime-500 text-white text-lg font-semibold rounded-full shadow-md hover:scale-105 hover:from-emerald-600 hover:to-lime-600 transition transform duration-200">
                        🚀 Hochladen & Loslegen
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
