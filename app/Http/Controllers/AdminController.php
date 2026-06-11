<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Listing;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        // withTrashed() includes soft-deleted listings so admin can see and restore them
        $listings = Listing::withTrashed()->with(['user', 'category'])->latest()->get();
        return view('admin.index', compact('users', 'listings'));
    }

    public function blockUser(User $user)
    {
        $user->update(['role' => 'blocked']);
        $this->log('Blocked user: '.$user->email);
        return back();
    }

    public function unblockUser(User $user)
    {
        $user->update(['role' => 'registered']);
        $this->log('Unblocked user: '.$user->email);
        return back();
    }

    public function restoreListing(Listing $listing)
    {
        // restore() sets deleted_at back to null, so it shows up again
        $listing->restore();
        $this->log('Restored listing: '.$listing->title);
        return back();
    }

    public function auditLogs()
    {
        $logs = session()->get('audit_logs', []);
        return view('admin.audit', compact('logs'));
    }

    // stored in session so it clears on logout, good enough for now
    private function log($msg)
    {
        $logs = session()->get('audit_logs', []);
        $logs[] = ['message' => $msg, 'time' => now()->toDateTimeString(), 'by' => auth()->user()->email];
        session()->put('audit_logs', $logs);
    }
}
