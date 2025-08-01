<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Menyimpan komentar baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'commentable_id' => 'required',
            'commentable_type' => 'required|string',
        ]);

        $commentableModel = app($validated['commentable_type']);
        $commentable = $commentableModel->findOrFail($validated['commentable_id']);

        $commentable->comments()->create([
            'user_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
