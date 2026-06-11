@extends('layouts.app')

@section('content')

<div class="admin-panel">
    <h2>{{ __('messages.admin_panel') }}</h2>

    <a href="{{ route('admin.auditLogs') }}" class="btn-red">{{ __('messages.audit_logs') }}</a>

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
                @if($user->role === 'blocked')
                    <form action="{{ route('admin.unblockUser', $user) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-red">{{ __('messages.unblock') }}</button>
                    </form>
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

    {{-- withTrashed in the controller means deleted listings show up here too --}}
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
            <td>{{ $listing->deleted_at ? 'Deleted' : 'Active' }}</td>
            <td>
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
