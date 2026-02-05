<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Scholar;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $query = Grade::with(['scholar.applicant.user', 'encoder']);

        if ($request->has('scholar_id')) {
            $query->where('scholar_id', $request->scholar_id);
        }

        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->has('school_year')) {
            $query->where('school_year', $request->school_year);
        }

        return response()->json($query->orderBy('school_year', 'desc')->orderBy('semester')->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scholar_id' => 'required|exists:scholars,id',
            'subject_code' => 'required|string|max:50',
            'subject_name' => 'required|string|max:255',
            'grade' => 'required|numeric|min:0|max:5',
            'units' => 'required|integer|min:1',
            'semester' => 'required|string',
            'school_year' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $validated['encoded_by'] = $request->user()->id;

        $grade = Grade::create($validated);

        // Recalculate GWA
        $scholar = Scholar::find($validated['scholar_id']);
        $this->calculateGWA($scholar);

        return response()->json($grade->load(['scholar.applicant.user', 'encoder']), 201);
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'subject_code' => 'sometimes|string|max:50',
            'subject_name' => 'sometimes|string|max:255',
            'grade' => 'sometimes|numeric|min:0|max:5',
            'units' => 'sometimes|integer|min:1',
            'semester' => 'sometimes|string',
            'school_year' => 'sometimes|string',
            'remarks' => 'nullable|string',
        ]);

        $grade->update($validated);

        // Recalculate GWA
        $this->calculateGWA($grade->scholar);

        return response()->json($grade->load(['scholar.applicant.user', 'encoder']));
    }

    public function destroy(Grade $grade)
    {
        $scholar = $grade->scholar;
        $grade->delete();

        // Recalculate GWA
        $this->calculateGWA($scholar);

        return response()->json(['message' => 'Grade deleted successfully']);
    }

    private function calculateGWA(Scholar $scholar)
    {
        $grades = $scholar->grades()
            ->selectRaw('SUM(grade * units) as total_points, SUM(units) as total_units')
            ->first();

        if ($grades && $grades->total_units > 0) {
            $gwa = $grades->total_points / $grades->total_units;
            $scholar->update(['current_gwa' => round($gwa, 2)]);
        }
    }
}
