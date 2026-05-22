<?php

namespace App\Http\Controllers;

use App\Models\DictionaryEntry;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    /**
     * Display the Kamus (dictionary) search page.
     *
     * Uses server-side GET search — clean, bookmarkable URLs, no Livewire/Ajax
     * needed. The debounce is handled client-side in the view (vanilla JS).
     */
    public function index(Request $request)
    {
        $query   = trim($request->input('q', ''));
        $entries = null;

        if ($query !== '') {
            $entries = DictionaryEntry::search($query)->paginate(20)->withQueryString();
        }

        return view('dictionary.index', [
            'entries' => $entries,
            'query'   => $query,
        ]);
    }
}
