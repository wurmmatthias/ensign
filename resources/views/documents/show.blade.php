<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        <!-- Dokumenten-Titel -->
        <h2 class="text-2xl font-bold text-gray-800">{{ $document->title }}</h2>

        <!-- PDF-Vorschau mit Klick-Position -->
        <div class="relative border rounded-xl shadow-md overflow-hidden">
            <div id="pdf-container" class="relative w-full bg-white"></div>
        </div>

        <!-- Signaturfeld -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">✍️ Unterschrift</h3>

            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- Canvas -->
                <div class="border-2 border-dashed border-gray-400 rounded-lg">
                    <canvas 
                        id="signature-pad" 
                        class="bg-gray-50 rounded-md" 
                        width="600" 
                        height="200"
                    ></canvas>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col gap-3">
                    <button id="clear" type="button"
                        class="bg-gray-200 text-gray-800 hover:bg-gray-300 px-4 py-2 rounded transition">
                        Zurücksetzen
                    </button>
                    <button id="save" type="button"
                        class="bg-green-600 text-white hover:bg-green-700 px-4 py-2 rounded transition">
                        Unterschrift speichern
                    </button>
                </div>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                📍 Klicke auf das Dokument oben, um die gewünschte Position für die Unterschrift festzulegen.
            </div>

            <!-- Formular -->
            <form id="signature-form" 
                  action="{{ route('signature.store', $document) }}" 
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

    <script>
        // Signature Pad initialisieren
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);
        const clearBtn = document.getElementById('clear');
        const saveBtn = document.getElementById('save');
        const input = document.getElementById('signature-input');
        const form = document.getElementById('signature-form');

        clearBtn.addEventListener('click', () => signaturePad.clear());

        saveBtn.addEventListener('click', () => {
            if (signaturePad.isEmpty()) {
                alert("Bitte zuerst unterschreiben.");
            } else if (!selectedPosition) {
                alert("Bitte zuerst auf das Dokument klicken, um eine Position festzulegen.");
            } else {
                input.value = signaturePad.toDataURL();
                document.getElementById('pos-x').value = selectedPosition.x;
                document.getElementById('pos-y').value = selectedPosition.y;
                form.submit();
            }
        });

        // PDF.js laden und Position ermitteln
        const url = "{{ Storage::url($document->file_path) }}";
        const container = document.getElementById("pdf-container");
        let selectedPosition = null;
        const scale = 1.5;

        pdfjsLib.getDocument(url).promise.then(pdf => {
            return pdf.getPage(1);
        }).then(page => {
            const viewport = page.getViewport({ scale });
            const canvas = document.createElement("canvas");
            const context = canvas.getContext("2d");

            canvas.width = viewport.width;
            canvas.height = viewport.height;
            canvas.classList.add("cursor-crosshair", "block");

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            page.render(renderContext);

            document.getElementById('canvas-width').value = canvas.width;
            document.getElementById('canvas-height').value = canvas.height;


            // Klickposition auf dem Canvas erfassen
            canvas.addEventListener("click", (event) => {
                const rect = canvas.getBoundingClientRect();
                const x = Math.round(event.clientX - rect.left);
                const y = Math.round(event.clientY - rect.top);

                selectedPosition = { x: x, y: y, page: 1 };

                // Vorherige Marker entfernen
                const oldMarker = container.querySelector(".position-marker");
                if (oldMarker) oldMarker.remove();

                // Marker anzeigen
                const marker = document.createElement("div");
                marker.className = "position-marker absolute bg-green-600 rounded-full border-2 border-white shadow";
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
