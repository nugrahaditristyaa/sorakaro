<?php

namespace App\Http\Controllers;

use App\Models\FlashcardCategory;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    /**
     * Display the flashcard category listing page.
     */
    public function index()
    {
        $categories = FlashcardCategory::withCount('flashcards')
            ->orderBy('order')
            ->get();

        return view('flashcards.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Display the interactive flashcard study page for a category.
     */
    public function show(FlashcardCategory $category)
    {
        $flashcards = $category->flashcards()->orderBy('order')->get();

        $flashcardsJson = $flashcards->map(function ($f) {
            return [
                'karo'         => $f->karo_word,
                'indo'         => $f->indonesian_translation,
                'example'      => $f->example_sentence,
                'exampleTrans' => $f->example_translation,
            ];
        })->values();

        return view('flashcards.show', [
            'category'       => $category,
            'flashcards'     => $flashcards,
            'flashcardsJson' => $flashcardsJson,
        ]);
    }
}
