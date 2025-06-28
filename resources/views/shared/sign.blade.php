<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-8">

        <!-- Hinweis für öffentlichen Link -->
        @if(request()->is('share/*'))
            <div class="flex items-center gap-2 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-2 shadow-sm">
                <span class="text-xl">🔓</span>
                <span>Ihnen wurde ein Dokument zur Unterzeichnung bereitgestellt.</span>
            </div>
        @endif

        <!-- Dokumenttitel -->
        <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">{{ $document->title }}</h2>

        <!-- PDF Vorschau -->
        <div class="relative border border-gray-300 rounded-lg shadow overflow-hidden">
            <div id="pdf-container" class="relative w-full bg-white"></div>
        </div>

        <!-- Signaturfeld -->
        <div class="bg-white border border-gray-200 rounded-lg shadow p-6 space-y-6">
            <h3 class="text-lg font-medium text-gray-700">✍️ Dokument unterschreiben</h3>

            <div class="flex flex-col md:flex-row gap-6 items-start">
                <!-- Canvas -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg">
                    <canvas
                        id="signature-pad"
                        class="bg-gray-50 rounded-md"
                        width="500"
                        height="160"
                    ></canvas>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col gap-3">
                    <button id="clear" type="button"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded transition">
                        Zurücksetzen
                    </button>
                    <button id="save" type="button"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded transition">
                        Unterschrift speichern
                    </button>
                </div>
            </div>

            <p class="text-xs text-gray-500">
                📍 Klicke auf das Dokument oben, um die gewünschte Unterschriftsposition zu wählen.
            </p>

            <!-- Formular -->
            <form id="signature-form"
                  action="{{ route('shared.sign.store', $link->token) }}"
                  method="POST">
                @csrf
                <input type="hidden" name="signature" id="signature-input">
                <input type="hidden" name="pos_x" id="pos-x">
                <input type="hidden" name="pos_y" id="pos-y">
                <input type="hidden" name="pos_page" id="pos-page" value="1">
                <input type="hidden" name="canvas_width" id="canvas-width">
                <input type="hidden" name="canvas_height" id="canvas-height">
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

    <script>
        const signaturePad = new SignaturePad(document.getElementById('signature-pad'));
        const clearBtn = document.getElementById('clear');
        const saveBtn = document.getElementById('save');
        const input = document.getElementById('signature-input');
        const form = document.getElementById('signature-form');
        let selectedPosition = null;

        clearBtn.addEventListener('click', () => signaturePad.clear());

        saveBtn.addEventListener('click', () => {
            if (signaturePad.isEmpty() || !selectedPosition) {
                alert("Bitte zuerst unterschreiben und eine Position im Dokument wählen.");
            } else {
                input.value = signaturePad.toDataURL();
                document.getElementById('pos-x').value = selectedPosition.x;
                document.getElementById('pos-y').value = selectedPosition.y;
                document.getElementById('canvas-width').value = canvas.width;
                document.getElementById('canvas-height').value = canvas.height;
                form.submit();
            }
        });

        const url = "{{ Storage::url($document->file_path) }}";
        const container = document.getElementById("pdf-container");
        const scale = 1.5;
        let canvas;

        pdfjsLib.getDocument(url).promise.then(pdf => pdf.getPage(1)).then(page => {
            const viewport = page.getViewport({ scale });
            canvas = document.createElement("canvas");
            const context = canvas.getContext("2d");

            canvas.width = viewport.width;
            canvas.height = viewport.height;
            canvas.classList.add("cursor-crosshair", "block");

            page.render({ canvasContext: context, viewport: viewport }).promise.then(() => {
                document.getElementById('canvas-width').value = canvas.width;
                document.getElementById('canvas-height').value = canvas.height;
            });

            canvas.addEventListener("click", (e) => {
                const rect = canvas.getBoundingClientRect();
                const x = Math.round(e.clientX - rect.left);
                const y = Math.round(e.clientY - rect.top);

                selectedPosition = { x, y, page: 1 };

                container.querySelectorAll(".position-marker").forEach(el => el.remove());

                const marker = document.createElement("div");
                marker.className = "position-marker absolute bg-emerald-600 rounded-full border-2 border-white shadow";
                marker.style.width = "14px";
                marker.style.height = "14px";
                marker.style.left = `${x - 7}px`;
                marker.style.top = `${y - 7}px`;
                marker.style.position = "absolute";
                marker.style.zIndex = 10;

                container.appendChild(marker);
            });

            container.appendChild(canvas);
        });
    </script>
</x-app-layout>
