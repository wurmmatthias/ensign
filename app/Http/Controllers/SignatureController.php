<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class SignatureController extends Controller
{
public function store(Request $request, Document $document)
{
    $request->validate([
        'signature' => 'required|string',
        'pos_x' => 'required|numeric',
        'pos_y' => 'required|numeric',
        'pos_page' => 'required|numeric',
    ]);

    // 1. Signatur dekodieren und speichern
    $imageData = $request->input('signature');
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = base64_decode($imageData);

    $signatureFilename = 'signatures/' . Str::uuid() . '.png';
    Storage::disk('public')->put($signatureFilename, $imageData);
    $signaturePath = Storage::disk('public')->path($signatureFilename);

    // 2. Ursprüngliche PDF öffnen
    $originalPdfPath = Storage::disk('public')->path($document->file_path);
    $signedPdfPath = 'signed/' . Str::uuid() . '.pdf';
    $signedPdfFullPath = Storage::disk('public')->path($signedPdfPath);

    $x = $request->input('pos_x');
    $y = $request->input('pos_y');
    $pageNum = (int) $request->input('pos_page');

    // 3. Neue PDF mit FPDI erzeugen
    $pdf = new Fpdi();
    $pageCount = $pdf->setSourceFile($originalPdfPath);
    

    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        $templateId = $pdf->importPage($pageNo);
        $size = $pdf->getTemplateSize($templateId);

        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($templateId);

            //$pdf->SetDrawColor(255, 0, 0);
            //$pdf->Rect(10, 10, 60, 20, 'D');

        // Auf der gewünschten Seite: Signatur einfügen
        if ($pageNo === $pageNum) {
            $canvasWidth = $request->input('canvas_width');
            $canvasHeight = $request->input('canvas_height');

            $scaleX = $size['width'] / $canvasWidth;
            $scaleY = $size['height'] / $canvasHeight;

            $pdfX = $x * $scaleX;
            $pdfY = $y * $scaleY;
            //$pdfY = $size['height'] - $pdfY - 20;

            $pdf->Image($signaturePath, $pdfX, $pdfY, 60, 20);

        }
    }

    Storage::disk('public')->makeDirectory('signed');
    // 4. Neue Datei speichern
    $pdf->Output($signedPdfFullPath, 'F');

    // 5. Optional: neuen Pfad in DB speichern oder Nutzer weiterleiten
    return redirect()->route('documents.index')->with('success', 'Dokument unterschrieben! 📄');

    // Optional: signiertes Dokument in DB verknüpfen:
    // $document->update(['signed_path' => $signedPdfPath]);
}

}
