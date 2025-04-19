<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $user = $request->user();
        $transactions = $user->transactions()
            ->selectRaw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income')
            ->selectRaw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            ->selectRaw('SUM(CASE WHEN type = "transfer" THEN amount ELSE 0 END) as total_transfer')
            ->selectRaw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) - SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as balance')
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->first();

        return response()->json(['data' => $transactions]);
    }
}
