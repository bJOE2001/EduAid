<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Scholar;
use App\Models\Scholarship;
use App\Models\Disbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalApplicants = Application::count();
        $pendingApplications = Application::where('status', 'pending')->count();
        $approvedApplications = Application::where('status', 'approved')->count();
        $rejectedApplications = Application::where('status', 'rejected')->count();
        $totalScholars = Scholar::where('status', 'active')->count();
        $totalBudget = Scholarship::sum('budget');
        $usedBudget = Disbursement::where('status', 'released')->sum('amount');
        $totalDisbursements = Disbursement::where('status', 'released')->count();

        return response()->json([
            'total_applicants' => $totalApplicants,
            'pending_applications' => $pendingApplications,
            'approved_applications' => $approvedApplications,
            'rejected_applications' => $rejectedApplications,
            'total_scholars' => $totalScholars,
            'total_budget' => $totalBudget,
            'used_budget' => $usedBudget,
            'remaining_budget' => $totalBudget - $usedBudget,
            'total_disbursements' => $totalDisbursements,
        ]);
    }

    public function applicantsReport(Request $request)
    {
        $query = Application::with(['applicant.user', 'scholarship']);

        if ($request->has('scholarship_id')) {
            $query->where('scholarship_id', $request->scholarship_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function scholarsReport(Request $request)
    {
        $query = Scholar::with(['applicant.user', 'scholarship']);

        if ($request->has('scholarship_id')) {
            $query->where('scholarship_id', $request->scholarship_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function financialReport(Request $request)
    {
        $query = Disbursement::with(['scholar.applicant.user', 'scholar.scholarship']);

        if ($request->has('date_from')) {
            $query->whereDate('released_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('released_date', '<=', $request->date_to);
        }

        $disbursements = $query->where('status', 'released')->get();

        $summary = [
            'total_amount' => $disbursements->sum('amount'),
            'by_type' => $disbursements->groupBy('type')->map->sum('amount'),
            'by_scholarship' => $disbursements->groupBy('scholar.scholarship.name')->map->sum('amount'),
            'count' => $disbursements->count(),
            'disbursements' => $disbursements,
        ];

        return response()->json($summary);
    }

    public function statistics(Request $request)
    {
        $applicationsByStatus = Application::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $scholarsByStatus = Scholar::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $applicationsByScholarship = Application::with('scholarship')
            ->select('scholarship_id', DB::raw('count(*) as count'))
            ->groupBy('scholarship_id')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->scholarship->name ?? 'Unknown' => $item->count];
            });

        $genderDistribution = Application::with('applicant')
            ->whereHas('applicant')
            ->get()
            ->groupBy('applicant.gender')
            ->map->count();

        return response()->json([
            'applications_by_status' => $applicationsByStatus,
            'scholars_by_status' => $scholarsByStatus,
            'applications_by_scholarship' => $applicationsByScholarship,
            'gender_distribution' => $genderDistribution,
        ]);
    }
}
