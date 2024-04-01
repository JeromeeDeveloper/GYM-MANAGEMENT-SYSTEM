<?php

namespace App\Http\Controllers\Admin;

use App\Models\CheckIns;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Member; // Make sure to import the Member model

class ReportsController extends Controller
{
    public function index()
    {
        // Retrieve members who have checked in
        $members = CheckIns::paginate(5);


        // Pass the $members variable to the view
        return view('reports', compact('members'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'member_id' => 'required|exists:members,id'
        ]);

        // Create a new check-in record
        $checkIn = new CheckIn();
        $checkIn->member_id = $request->member_id;
        $checkIn->save();

        // Return a success response
        return response()->json(['message' => 'Check-in recorded successfully.'], 200);
    }


}
