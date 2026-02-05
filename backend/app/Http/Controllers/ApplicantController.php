<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Applicant::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'zip_code' => 'required|string',
            'student_number' => 'nullable|string|unique:applicants',
            'school_name' => 'required|string',
            'course' => 'required|string',
            'year_level' => 'required|integer|min:1',
            'gwa' => 'nullable|numeric|min:0|max:5',
            'family_income' => 'required|numeric|min:0',
            'family_members' => 'required|integer|min:1',
            'father_name' => 'nullable|string',
            'father_occupation' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'mother_occupation' => 'nullable|string',
            'guardian_name' => 'nullable|string',
            'guardian_relationship' => 'nullable|string',
            'guardian_contact' => 'nullable|string',
        ]);

        $validated['user_id'] = $request->user()->id;

        $applicant = Applicant::create($validated);

        return response()->json($applicant->load('user'), 201);
    }

    public function show(Applicant $applicant)
    {
        return response()->json($applicant->load(['user', 'applications.scholarship']));
    }

    public function update(Request $request, Applicant $applicant)
    {
        // Ensure user can only update their own profile
        if ($applicant->user_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'birth_date' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            'phone' => 'nullable|string',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'province' => 'sometimes|string',
            'zip_code' => 'sometimes|string',
            'student_number' => 'nullable|string|unique:applicants,student_number,' . $applicant->id,
            'school_name' => 'sometimes|string',
            'course' => 'sometimes|string',
            'year_level' => 'sometimes|integer|min:1',
            'gwa' => 'nullable|numeric|min:0|max:5',
            'family_income' => 'sometimes|numeric|min:0',
            'family_members' => 'sometimes|integer|min:1',
            'father_name' => 'nullable|string',
            'father_occupation' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'mother_occupation' => 'nullable|string',
            'guardian_name' => 'nullable|string',
            'guardian_relationship' => 'nullable|string',
            'guardian_contact' => 'nullable|string',
        ]);

        $applicant->update($validated);

        return response()->json($applicant->load('user'));
    }
}
