<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function index(Request $request)
    {
        $attempts = Attempt::with('lesson.level')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('attempts.index', compact('attempts'));
    }
}
