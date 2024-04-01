<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payments;
use Illuminate\Http\Request;
use Carbon\Carbon;


class PayController extends Controller
{
    public function index()
    {

        $members = Payments::paginate(5);

        return view('pay', compact('members'));
    }

    public function filterData2(Request $request)
{
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');
    $filterType = $request->input('filterType');
    $parsedStartDate = Carbon::parse($startDate)->startOfDay();
    $parsedEndDate = Carbon::parse($endDate)->endOfDay();
    $filteredPayments = Payments::whereBetween('created_at', [$parsedStartDate, $parsedEndDate])->get();

    $html = '';
    foreach ($filteredPayments as $payment) {
        $html .= '<tr>';
        $html .= '<td>' . $payment->member->full_name . '</td>';
        $html .= '<td>' . $payment->amount . '</td>';
        $html .= '<td>' . $payment->type . '</td>';
        $html .= '<td>' . $payment->payment_for . '</td>';
        $html .= '</tr>';
    }

    return $html;
}

}

