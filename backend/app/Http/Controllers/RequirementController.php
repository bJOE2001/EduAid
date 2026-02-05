<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    public function index(Request $request)
    {
        $query = Requirement::with('scholarship');

        if ($request->has('scholarship_id')) {
            $query->where('scholarship_id', $request->scholarship_id);
        }

        return response()->json($query->orderBy('order')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
            'file_type' => 'nullable|string',
            'order' => 'integer',
        ]);

        $requirement = Requirement::create($validated);

        return response()->json($requirement->load('scholarship'), 201);
    }

    public function update(Request $request, Requirement $requirement)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
            'file_type' => 'nullable|string',
            'order' => 'integer',
        ]);

        $requirement->update($validated);

        return response()->json($requirement->load('scholarship'));
    }

    public function destroy(Requirement $requirement)
    {
        $requirement->delete();

        return response()->json(['message' => 'Requirement deleted successfully']);
    }
}
