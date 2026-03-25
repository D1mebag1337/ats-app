<?php

namespace App\Http\Controllers;

use App\Models\Bewerbung;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isHR() || $user->Role === 'R') {
            // Recruiter: see all applications
            $bewerbungen = Bewerbung::with('stelle:StellenID,Name', 'bewerber:UserID,Vorname,Name')
                ->select(['BewerbungID', 'UserID', 'StellenID', 'Status'])
                ->latest()
                ->get();
        } else {
            // Applicant: see only their own
            $bewerbungen = Bewerbung::with('stelle:StellenID,Name')
                ->where('UserID', $user->UserID)
                ->select(['BewerbungID', 'UserID', 'StellenID', 'Status'])
                ->latest()
                ->get();
        }

        $isRecruiter = $user->isHR() || $user->Role === 'R';

        return Inertia::render('Dashboard', [
            'bewerbungen' => $bewerbungen,
            'isRecruiter' => $isRecruiter,
        ]);
    }
}
