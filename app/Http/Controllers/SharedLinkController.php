<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SharedLink;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;

class SharedLinkController extends Controller
{


public function show($token)
{
    $link = SharedLink::where('token', $token)->firstOrFail();

    if (now()->greaterThan($link->expires_at)) {
        abort(403, 'Dieser Link ist abgelaufen.');
    }

    $document = $link->document;

    return view('shared.sign', compact('document', 'link'));
}

public function sign(Request $request, $token)
{
    $request->validate([
        'signature' => 'required|string',
        'pos_x' => 'required|numeric',
        'pos_y' => 'required|numeric',
        'pos_page' => 'required|numeric',
        'canvas_width' => 'required|numeric',
        'canvas_height' => 'required|numeric',
    ]);

    $link = SharedLink::where('token', $token)->firstOrFail();

    if (now()->greaterThan($link->expires_at)) {
        abort(403, 'Dieser Link ist abgelaufen.');
    }

    $document = $link->document;
    if (!$document) {
        abort(404, 'Dokument nicht gefunden.');
    }

    // Signatur decodieren
    $imageData = str_replace('data:image/png;base64,', '', $request->input('signature'));
    $imageData = base64_decode($imageData);
    $signatureFilename = 'signatures/' . Str::uuid() . '.png';
    Storage::disk('public')->put($signatureFilename, $imageData);
    $signaturePath = Storage::disk('public')->path($signatureFilename);

    // Original PDF Pfad
    $originalPdfPath = Storage::disk('public')->path($document->file_path);
    $signedPdfPath = 'signed/' . Str::uuid() . '.pdf';
    $signedPdfFullPath = Storage::disk('public')->path($signedPdfPath);

    // Umrechnung
    $x = $request->input('pos_x');
    $y = $request->input('pos_y');
    $canvasWidth = $request->input('canvas_width');
    $canvasHeight = $request->input('canvas_height');
    $pageNum = (int) $request->input('pos_page');

    $pdf = new Fpdi();
    $pageCount = $pdf->setSourceFile($originalPdfPath);

    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        $templateId = $pdf->importPage($pageNo);
        $size = $pdf->getTemplateSize($templateId);
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($templateId);

        if ($pageNo === $pageNum) {
            $scaleX = $size['width'] / $canvasWidth;
            $scaleY = $size['height'] / $canvasHeight;
            $pdfX = $x * $scaleX;
            $pdfY = $y * $scaleY;
            //$pdfY = $size['height'] - $pdfY - 20;

            $pdf->Image($signaturePath, $pdfX, $pdfY, 60, 20);
        }
    }

    Storage::disk('public')->makeDirectory('signed');
    $pdf->Output($signedPdfFullPath, 'F');

    return redirect()
        ->route('documents.signed-preview', ['path' => basename($signedPdfPath)])
        ->with('signed_success', true);
}
}
