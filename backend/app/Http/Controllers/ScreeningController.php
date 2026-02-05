<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use App\Models\ScreeningScore;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScreeningController extends Controller
{
    public function index(Request $request)
    {
        $query = Screening::with(['scholarship', 'creator']);

        if ($request->has('scholarship_id')) {
            $query->where('scholarship_id', $request->scholarship_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'criteria' => 'required|array',
            'screening_date' => 'nullable|date',
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['status'] = 'scheduled';

        $screening = Screening::create($validated);

        return response()->json($screening->load('scholarship', 'creator'), 201);
    }

    public function show(Screening $screening)
    {
        return response()->json($screening->load(['scholarship', 'creator', 'scores.application.applicant']));
    }

    public function scoreApplication(Request $request, Screening $screening, Application $application)
    {
        $validated = $request->validate([
            'scores' => 'required|array',
            'scores.*.criteria_name' => 'required|string',
            'scores.*.score' => 'required|numeric|min:0|max:100',
            'scores.*.weight' => 'required|numeric|min:0|max:1',
            'scores.*.remarks' => 'nullable|string',
        ]);

        DB::transaction(function() use ($screening, $application, $validated, $request) {
            foreach ($validated['scores'] as $scoreData) {
                ScreeningScore::updateOrCreate(
                    [
                        'screening_id' => $screening->id,
                        'application_id' => $application->id,
                        'criteria_name' => $scoreData['criteria_name'],
                    ],
                    [
                        'score' => $scoreData['score'],
                        'weight' => $scoreData['weight'],
                        'remarks' => $scoreData['remarks'] ?? null,
                        'scored_by' => $request->user()->id,
                    ]
                );
            }

            // Calculate total score
            $totalScore = ScreeningScore::where('screening_id', $screening->id)
                ->where('application_id', $application->id)
                ->selectRaw('SUM(score * weight) as total')
                ->value('total');

            $application->update([
                'total_score' => $totalScore,
                'status' => 'for_screening',
            ]);
        });

        return response()->json($application->load('screeningScores'));
    }

    public function rankApplications(Screening $screening)
    {
        $applications = Application::whereHas('screeningScores', function($q) use ($screening) {
            $q->where('screening_id', $screening->id);
        })
        ->with(['applicant.user', 'screeningScores' => function($q) use ($screening) {
            $q->where('screening_id', $screening->id);
        }])
        ->orderBy('total_score', 'desc')
        ->get();

        // Update ranks
        foreach ($applications as $index => $application) {
            $application->update(['rank' => $index + 1]);
        }

        return response()->json($applications);
    }
}
