<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function payment($id)
    {
       
        $member = \App\Models\Member::findOrFail($id);

        return view('admin.member.payment', compact('member'));
    }
}
