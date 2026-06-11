{{-- Admin control panel - manage users and listings in one place --}}
@extends('layouts.app')

@section('content')

<div class="admin-panel">
    <h2>{{ __('messages.admin_panel') }}</h2>

    <a href="{{ route('admin.auditLogs') }}" class="btn-red">{{ __('messages.audit_logs') }}</a>

    {{-- USERS TABLE --}}
    <h3>{{ __('messages.users') }}</h3>
    <table class="admin-table">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td>
                {{-- blocked users get an unblock button... --}}
                @if($user->role === 'blocked')
                    <form action="{{ route('admin.unblockUser', $user) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-red">{{ __('messages.unblock') }}</button>
                    </form>
                {{-- ...normal users get a block button, but we never show one for fellow admins --}}
                @elseif($user->role !== 'admin')
                    <form action="{{ route('admin.blockUser', $user) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-delete">{{ __('messages.block') }}</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </table>

    {{-- LISTINGS TABLE - includes soft-deleted ones so they can be restored --}}
    <h3>{{ __('messages.listings') }}</h3>
    <table class="admin-table">
        <tr>
            <th>Title</th>
            <th>User</th>
            <th>Category</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        @foreach($listings as $listing)
        <tr>
            <td>{{ $listing->title }}</td>
            <td>{{ $listing->user->name }}</td>
            <td>{{ $listing->category->name }}</td>
            {{-- deleted_at being set is how we know it was soft-deleted --}}
            <td>{{ $listing->deleted_at ? 'Deleted' : 'Active' }}</td>
            <td>
                {{-- deleted listing -> restore button, active listing -> delete button --}}
                @if($listing->deleted_at)
                    <form action="{{ route('admin.restoreListing', $listing) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-red">{{ __('messages.restore') }}</button>
                    </form>
                @else
                    <form action="{{ route('listings.destroy', $listing) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">{{ __('messages.delete') }}</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>

@endsection