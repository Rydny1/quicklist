<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Listing;

// Admin-only actions. The 'admin' middleware on the routes keeps everyone else out.
class AdminController extends Controller
{
    // dashboard for the admin - lists every user and every listing
    public function index()
    {
        $users = User::all();
        // withTrashed() so soft-deleted listings still show up (admin can restore them)
        $listings = Listing::withTrashed()->with(['user', 'category'])->latest()->get();
        return view('admin.index', compact('users', 'listings'));
    }

    // block a user by flipping their role - they can't post anymore
    public function blockUser(User $user)
    {
        $user->update(['role' => 'blocked']);
        $this->log('Blocked user: '.$user->email); // keep a record of it
        return back();
    }

    // undo the block - back to a normal registered user
    public function unblockUser(User $user)
    {
        $user->update(['role' => 'registered']);
        $this->log('Unblocked user: '.$user->email);
        return back();
    }

    // bring back a listing that was soft-deleted
    public function restoreListing(Listing $listing)
    {
        // have to use withTrashed() to even find it, since it's "deleted"
        Listing::withTrashed()->find($listing->id)->restore();
        $this->log('Restored listing: '.$listing->title);
        return back();
    }

    // simple page that prints out everything admins have done
    public function auditLogs()
    {
        $logs = session()->get('audit_logs', []);
        return view('admin.audit', compact('logs'));
    }

    // little helper that appends an entry to the audit log.
    // note: stored in the session for now, so it resets when the admin logs out
    private function log($msg)
    {
        $logs = session()->get('audit_logs', []);
        $logs[] = ['message' => $msg, 'time' => now()->toDateTimeString(), 'by' => auth()->user()->email];
        session()->put('audit_logs', $logs);
    }
}