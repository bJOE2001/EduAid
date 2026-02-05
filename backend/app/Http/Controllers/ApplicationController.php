<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['applicant.user', 'scholarship', 'documents']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('scholarship_id')) {
            $query->where('scholarship_id', $request->scholarship_id);
        }

        if ($request->user()->hasRole('applicant')) {
            $query->whereHas('applicant', function($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            });
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'documents' => 'required|array',
            'documents.*.requirement_id' => 'required|exists:requirements,id',
            'documents.*.file' => 'required|file|max:10240', // 10MB max
        ]);

        $applicant = $request->user()->applicant;

        if (!$applicant) {
            return response()->json(['message' => 'Applicant profile not found'], 404);
        }

        $application = Application::create([
            'applicant_id' => $applicant->id,
            'scholarship_id' => $validated['scholarship_id'],
            'status' => 'pending',
        ]);

        // Log audit
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'created',
            'model_type' => 'Application',
            'model_id' => $application->id,
            'new_values' => $application->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        foreach ($validated['documents'] as $doc) {
            $file = $doc['file'];
            $path = $file->store('applications/' . $application->id, 'public');

            ApplicationDocument::create([
                'application_id' => $application->id,
                'requirement_id' => $doc['requirement_id'],
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        return response()->json($application->load('documents'), 201);
    }

    public function show(Application $application)
    {
        return response()->json($application->load([
            'applicant.user',
            'scholarship.requirements',
            'documents.requirement',
            'reviewer',
            'approver'
        ]));
    }

    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,under_review,for_screening,approved,rejected,on_hold',
            'remarks' => 'nullable|string',
        ]);

        if (isset($validated['status'])) {
            if ($validated['status'] === 'under_review') {
                $validated['reviewed_by'] = $request->user()->id;
                $validated['reviewed_at'] = now();
            } elseif ($validated['status'] === 'approved') {
                $validated['approved_by'] = $request->user()->id;
                $validated['approved_at'] = now();
            }
        }

        $application->update($validated);

        return response()->json($application->load('reviewer', 'approver'));
    }

    public function verifyDocument(Request $request, ApplicationDocument $document)
    {
        $validated = $request->validate([
            'is_verified' => 'required|boolean',
            'verification_notes' => 'nullable|string',
        ]);

        $document->update([
            'is_verified' => $validated['is_verified'],
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
            'verification_notes' => $validated['verification_notes'] ?? null,
        ]);

        return response()->json($document);
    }
}
