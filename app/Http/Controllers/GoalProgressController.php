<?php

namespace App\Http\Controllers;

use App\Models\Goal;

class GoalProgressController extends Controller
{
    public function index(Goal $goal)
    {
        $progressEntries = $goal->progress()
            ->orderBy('period_start', 'desc')
            ->get();

        return response()->json($progressEntries);
    }
}
