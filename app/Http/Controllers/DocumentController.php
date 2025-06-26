<?php

namespace App\Http\Controllers;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class DocumentController extends Controller
{
    use AuthorizesRequests;


    public function index()
{
    $documents = auth()->user()->documents;
    return view('documents.index', compact('documents'));
}

public function create()
{
    return view('documents.create');
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'document' => 'required|file|mimes:pdf|max:10240',
    ]);

    $path = $request->file('document')->store('documents', 'public');

    $document = Document::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'file_path' => $path,
    ]);

    return redirect()->route('documents.show', $document);
}

public function show(Document $document)
{
    $this->authorize('view', $document);

    return view('documents.show', compact('document'));
}
}
