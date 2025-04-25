<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function getData(Request $request)
    {
        $query = User::with('alliance:id,name')->select(['id', 'name', 'email', 'role', 'created_at', 'alliance_id'])->whereIn('role', ['player', 'king']);

        $dataTable = DataTables::of($query)
            ->addColumn('alliance', fn($user) => $user->alliance->name ?? '—');

        $customSortMap = [];

        return $this->applyCustomSorting($request, $dataTable, $customSortMap)->make(true);
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('default123');
        $user->save();

        return request()->ajax()
            ? response()->json(['message' => 'Password has been reset to "default123"'])
            : redirect()->back()->with('success', 'Password has been reset to "default123"');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->gameData()->delete();
        $user->awards()->delete();
        AccessRequest::where('email', $user->email)->delete();

        $user->delete();

        return request()->ajax()
            ? response()->json(['message' => 'User and their game data deleted successfully'])
            : redirect()->back()->with('success', 'User and their game data deleted successfully');
    }
}
