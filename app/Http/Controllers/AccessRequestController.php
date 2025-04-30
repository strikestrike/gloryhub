<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\AccessApprovedMail;
use App\Mail\AccessRequestNotification;
use App\Models\AccessRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class AccessRequestController extends Controller
{
    public function index()
    {
        $requests = AccessRequest::where('status', 'pending')->get();
        return view('access_requests.index', compact('requests'));
    }

    public function getData(Request $request)
    {
        $query = AccessRequest::whereIn('status', ['pending']);

        $dataTable = DataTables::of($query);

        $customSortMap = [];

        return $this->applyCustomSorting($request, $dataTable, $customSortMap)->make(true);
    }

    public function approve($id)
    {
        $request = AccessRequest::findOrFail($id);
        $token = Str::random(40);

        $request->update([
            'status' => 'approved',
            'token' => $token,
            'token_expires_at' => now()->addHours(24),
        ]);

        Mail::to($request->email)->send(new AccessApprovedMail($request));

        return back()->with('success', 'Approved and email sent.');
    }

    public function deny($id)
    {
        AccessRequest::findOrFail($id)->update(['status' => 'denied']);
        return back()->with('error', 'Access denied.');
    }

    public function destroy($id)
    {
        $accessRequest = AccessRequest::findOrFail($id);
        $accessRequest->delete();

        return response()->json(['message' => 'Access request deleted successfully']);
    }

    public function showForm()
    {
        return view('access_requests.form');
    }

    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'kingdom' => 'required|string',
            'alliance' => 'required|string',
            'player_name' => 'required|string',
            'email' => 'required|email|unique:access_requests,email,status,pending',
        ]);

        AccessRequest::create($validated);

        $adminEmail = "k44notifications@gmail.com";
        Mail::to($adminEmail)->send(new AccessRequestNotification($validated));

        return redirect()->back()->with('message', 'Request submitted! Wait for approval.');
    }
}
