<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payments;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashflowController extends Controller
{
    public function index()
    {

        $payments = Payments::all();

        $totalCash = $payments->where('type', 'cash')->sum('amount');

        $totalGcash = $payments->where('type', 'gcash')->sum('amount');

        $members = Payments::paginate(5);

        return view('cashflow', compact('members', 'totalCash', 'totalGcash'));
    }

    private function calculateGcashTotalAmount($startDate, $endDate)
    {
        return Payments::where('type', 'gcash')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
    }

    public function filterData3(Request $request)
    {
        $startDate = Carbon::parse($request->input('startDate'))->startOfDay();
        $endDate = Carbon::parse($request->input('endDate'))->endOfDay();
        $filterType = $request->input('filterType');

        if ($filterType === 'session') {

            $endDate = $startDate->copy()->endOfDay();
        }

        $totalAmount = 0;

        if ($filterType === 'gcash') {

            $totalAmount = Payments::where('type', 'gcash')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
        } elseif ($filterType === 'cash') {

            $totalAmount = Payments::where('type', 'cash')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
        } else {

            $totalAmount = Payments::whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
        }

        $html = '<div class="card border-dark mb-3" style="max-width: 79rem;">';
        $html .= '<div class="card-header"><strong>' . ($filterType ? ucfirst($filterType === 'gcash' ? 'GCash' : $filterType) : 'Custom Date') . '</strong></div>';
        $html .= '<div class="card-body">';
        $html .= '<h5 class="card-title"><strong>Total Amount</strong></h5>';
        $html .= '<p class="card-text"><strong>₱ ' . number_format($totalAmount, 2) . '</strong></p>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }

    public function fetchChartData()
    {

        $cashChartData = CashPayment::selectRaw('SUM(amount) as total_amount, DATE_FORMAT(created_at, "%M") as month')
                                    ->groupBy('month')
                                    ->orderBy('created_at')
                                    ->get();

        $gcashChartData = GcashPayment::selectRaw('SUM(amount) as total_amount, DATE_FORMAT(created_at, "%M") as month')
                                      ->groupBy('month')
                                      ->orderBy('created_at')
                                      ->get();

        return response()->json(compact('cashChartData', 'gcashChartData'));
    }

}
