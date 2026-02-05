<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScholarController extends Controller
{
    public function index(Request $request)
    {
        $query = Scholar::with(['applicant.user', 'scholarship', 'application']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('scholarship_id')) {
            $query->where('scholarship_id', $request->scholarship_id);
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id|unique:scholars,application_id',
        ]);

        $application = Application::with(['applicant', 'scholarship'])->findOrFail($validated['application_id']);

        if ($application->status !== 'approved') {
            return response()->json(['message' => 'Application must be approved first'], 400);
        }

        $scholar = Scholar::create([
            'application_id' => $application->id,
            'scholarship_id' => $application->scholarship_id,
            'applicant_id' => $application->applicant_id,
            'scholar_number' => 'SCH-' . strtoupper(Str::random(8)),
            'start_date' => now(),
            'status' => 'active',
            'is_renewable' => true,
            'contract_signed_by' => $request->user()->id,
            'contract_signed_at' => now(),
        ]);

        // Update scholarship slots
        $application->scholarship->increment('current_slots');

        return response()->json($scholar->load(['applicant.user', 'scholarship']), 201);
    }

    public function show(Scholar $scholar)
    {
        return response()->json($scholar->load([
            'applicant.user',
            'scholarship',
            'application',
            'disbursements',
            'grades'
        ]));
    }

    public function update(Request $request, Scholar $scholar)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:active,suspended,graduated,terminated',
            'current_gwa' => 'nullable|numeric|min:0|max:5',
            'is_renewable' => 'boolean',
            'suspension_reason' => 'nullable|string|required_if:status,suspended',
            'termination_reason' => 'nullable|string|required_if:status,terminated',
        ]);

        $scholar->update($validated);

        return response()->json($scholar->load(['applicant.user', 'scholarship']));
    }

    public function calculateGWA(Scholar $scholar)
    {
        $grades = $scholar->grades()
            ->selectRaw('SUM(grade * units) as total_points, SUM(units) as total_units')
            ->first();

        if ($grades && $grades->total_units > 0) {
            $gwa = $grades->total_points / $grades->total_units;
            $scholar->update(['current_gwa' => $gwa]);

            return response()->json([
                'gwa' => round($gwa, 2),
                'total_units' => $grades->total_units,
            ]);
        }

        return response()->json(['message' => 'No grades found'], 404);
    }
}
