<?php

namespace App\Http\Controllers;

use App\Models\Disbursement;
use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DisbursementController extends Controller
{
    public function index(Request $request)
    {
        $query = Disbursement::with(['scholar.applicant.user', 'releaser']);

        if ($request->has('scholar_id')) {
            $query->where('scholar_id', $request->scholar_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return response()->json($query->orderBy('scheduled_date', 'desc')->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scholar_id' => 'required|exists:scholars,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:allowance,tuition,book,other',
            'scheduled_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $disbursement = Disbursement::create([
            'scholar_id' => $validated['scholar_id'],
            'reference_number' => 'DIS-' . strtoupper(Str::random(10)),
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'scheduled_date' => $validated['scheduled_date'],
            'status' => 'pending',
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return response()->json($disbursement->load(['scholar.applicant.user']), 201);
    }

    public function show(Disbursement $disbursement)
    {
        return response()->json($disbursement->load(['scholar.applicant.user', 'releaser']));
    }

    public function update(Request $request, Disbursement $disbursement)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,processing,released,cancelled',
            'released_date' => 'nullable|date|required_if:status,released',
            'remarks' => 'nullable|string',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'released') {
            $validated['released_by'] = $request->user()->id;
            $validated['released_date'] = $validated['released_date'] ?? now();
        }

        $disbursement->update($validated);

        return response()->json($disbursement->load(['scholar.applicant.user', 'releaser']));
    }

    public function release(Disbursement $disbursement)
    {
        $disbursement->update([
            'status' => 'released',
            'released_by' => request()->user()->id,
            'released_date' => now(),
        ]);

        return response()->json($disbursement->load(['scholar.applicant.user', 'releaser']));
    }
}
