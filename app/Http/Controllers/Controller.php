<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function searchMembers(Request $request)
    {
        $query = $request->input('query');
        $members = Member::where('code', 'like', '%' . $query . '%')->get();

        if ($members->isNotEmpty()) {
            foreach ($members as $member) {
                if ($member->code === $query) {
                    $latest_membership = Membership::where('member_id', $member->id)
                                                    ->latest()
                                                    ->first();

                    if ($latest_membership && $latest_membership->status === 'active' && !$this->isExpired($latest_membership->expiration_date)) {
                        $checkin = new CheckIns();
                        $checkin->member_id = $member->id;
                        $checkin->save();
                    }
                }
            }
        }

        $latest_memberships = Membership::whereIn('member_id', $members->pluck('id'))
                                         ->latest()
                                         ->get();

        return view('landingpage', compact('members', 'latest_memberships'));
    }
}


