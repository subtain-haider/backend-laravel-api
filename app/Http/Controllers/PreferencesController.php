<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function updatePreferences(Request $request)
    {
        $user = $request->user();

        $user->update([
            'preferred_sources' => $request->preferred_sources,
            'preferred_categories' => $request->preferred_categories,
            'preferred_authors' => $request->preferred_authors,
        ]);

        return response()->json(['message' => 'Preferences updated successfully']);
    }

    public function getPreferences(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'preferred_sources' => $user->preferred_sources,
            'preferred_categories' => $user->preferred_categories,
            'preferred_authors' => $user->preferred_authors,
        ]);
    }

}
