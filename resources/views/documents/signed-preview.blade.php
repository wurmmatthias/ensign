<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-lime-50 via-white to-emerald-50 py-12 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-emerald-700 flex items-center justify-center gap-2">
                    📄 Vorschau: Unterschriebenes Dokument
                </h1>
                <p class="text-gray-600 mt-2">Hier siehst du das finale PDF mit deiner Unterschrift.</p>
            </div>

            <div class="rounded-2xl overflow-hidden border border-emerald-200 bg-white shadow-xl">
                <iframe 
                    src="{{ asset('storage/signed/' . $signedPath) }}" 
                    class="w-full h-[750px] rounded-b-2xl"
                ></iframe>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row sm:justify-between items-center gap-4">
                <a href="{{ asset('storage/signed/' . $signedPath) }}" 
                   download 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-full hover:bg-emerald-700 transition shadow-md">
                    ⬇️ PDF herunterladen
                </a>

                <a href="{{ route('documents.index') }}" 
                   class="text-emerald-700 hover:underline text-sm">
                    🔙 Zurück zur Dokumentenübersicht
                </a>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 für Fancy Popups -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('signed_success'))
        <script>
            Swal.fire({
                title: 'Erfolg! 🎉',
                text: 'Dein Dokument wurde unterschrieben.',
                icon: 'success',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                backdrop: true
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
    if ({{ session('signed_success') ? 'true' : 'false' }}) {
        confetti({
            particleCount: 150,
            spread: 100,
            origin: { y: 0.6 }
        });
    }
</script>

</x-app-layout>
