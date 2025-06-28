<?php

namespace App\Http\Controllers;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\SharedLink;


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

public function share(Document $document)
{
    $sharedLink = SharedLink::create([
        'token' => Str::uuid(),
        'document_id' => $document->id,
        'expires_at' => now()->addHours(48),
    ]);

    $link = route('shared.sign', $sharedLink->token);
    session()->flash('share_link', route('shared.sign', $sharedLink->token));
    session()->flash('share_expires_at', $sharedLink->expires_at);

    return back()->with('share_link', $link);
}
}
