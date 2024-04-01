<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payments;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Membership;

class DmemberController extends Controller
{
    public function index(Request $request)
    {

        $query = Member::query();


        if ($request->has('search')) {

            $searchTerm = $request->input('search');
            $query->whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%$searchTerm%'");
        }

        $members = $query->paginate(5);

        $activeUsersData = Membership::where('status', 'active')
            ->where('start_date', '>=', Carbon::now()->subDays(7))
            ->groupBy('start_date')
            ->orderBy('start_date')
            ->get(['start_date', \DB::raw('count(*) as count')])
            ->pluck('count', 'start_date');

        $expiredUsersData = Membership::where('status', 'expired')
            ->where('end_date', '>=', Carbon::now()->subDays(7))
            ->groupBy('end_date')
            ->orderBy('end_date')
            ->get(['end_date', \DB::raw('count(*) as count')])
            ->pluck('count', 'end_date');

        $cancelledUsersData = Membership::where('annual_status', 'cancelled')
            ->where('annual_end_date', '>=', Carbon::now()->subDays(7))
            ->groupBy('annual_end_date')
            ->orderBy('annual_end_date')
            ->get(['annual_end_date', \DB::raw('count(*) as count')])
            ->pluck('count', 'annual_end_date');

        return view('Dmember', compact('members', 'activeUsersData', 'expiredUsersData', 'cancelledUsersData'));
    }

    public function filterData4(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $filterType = $request->input('filterType');

        $parsedStartDate = Carbon::parse($startDate)->startOfDay();
        $parsedEndDate = Carbon::parse($endDate)->endOfDay();

        $filteredCheckIns = Member::whereBetween('created_at', [$parsedStartDate, $parsedEndDate])->get();

        $html = '';
        foreach ($filteredCheckIns as $member) {
            $html .= '<tr>';
            $html .= '<td>' . $member->code . '</td>';
            $html .= '<td>' . $member->full_name . '</td>';
            $html .= '<td>' . $member->membership->status . '</td>';
            $html .= '<td>' . $member->membership->annual_status . '</td>';
            $html .= '<td>' . date('F j, Y g:i A', strtotime($member->membership->end_date)) . '</td>';
            $html .= '<td>' . date('F j, Y g:i A', strtotime($member->membership->annual_end_date)) . '</td>';
            $html .= '</tr>';
        }

        return $html;
    }
}

