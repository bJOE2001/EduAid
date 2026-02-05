<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index(Request $request)
    {
        $query = Scholarship::with('creator', 'requirements');

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:merit,need-based,sports,academic,other',
            'budget' => 'required|numeric|min:0',
            'allowance_per_month' => 'nullable|numeric|min:0',
            'max_slots' => 'required|integer|min:1',
            'application_start' => 'required|date',
            'application_end' => 'required|date|after:application_start',
            'qualifications' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['current_slots'] = 0;

        $scholarship = Scholarship::create($validated);

        return response()->json($scholarship->load('creator'), 201);
    }

    public function show(Scholarship $scholarship)
    {
        return response()->json($scholarship->load('creator', 'requirements', 'applications'));
    }

    public function update(Request $request, Scholarship $scholarship)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'type' => 'sometimes|in:merit,need-based,sports,academic,other',
            'budget' => 'sometimes|numeric|min:0',
            'allowance_per_month' => 'nullable|numeric|min:0',
            'max_slots' => 'sometimes|integer|min:1',
            'application_start' => 'sometimes|date',
            'application_end' => 'sometimes|date|after:application_start',
            'qualifications' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $scholarship->update($validated);

        return response()->json($scholarship->load('creator', 'requirements'));
    }

    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();

        return response()->json(['message' => 'Scholarship deleted successfully']);
    }
}
