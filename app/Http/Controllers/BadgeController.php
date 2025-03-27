<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{

    public function store(Request $request)
    {
        // dd("worked");
        $validated = $request->validate([
            'name' => 'required|string|unique:badges',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'rules' => 'array'
        ]);

        $badge = Badge::create($validated);

        if (isset($validated['rules'])) {
            foreach ($validated['rules'] as $rule) {
                $badge->rules()->create($rule);
            }
        }

        return response()->json($badge, 201);
    }

    public function update(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|unique:badges,name,'.$badge->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'rules' => 'array'
        ]);

        $badge->update($validated);

        if (isset($validated['rules'])) {
            $badge->rules()->delete();
            foreach ($validated['rules'] as $rule) {
                $badge->rules()->create($rule);
            }
        }

        return response()->json($badge);
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();
        return response()->json(["message"=>'badge deleted successfully '], 200);
    }
}
