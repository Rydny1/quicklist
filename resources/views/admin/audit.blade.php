{{-- Read-only list of admin actions (block/unblock/restore) --}}
@extends('layouts.app')

@section('content')

<div class="admin-panel">
    <h2>{{ __('messages.audit_logs') }}</h2>
    <a href="{{ route('admin.index') }}" class="btn-red">Back</a>

    {{-- logs is an array pulled from the session, so it might be empty --}}
    @if(count($logs) > 0)
        <table class="admin-table">
            <tr>
                <th>Action</th>
                <th>By</th>
                <th>Time</th>
            </tr>
            {{-- print each logged action as a row --}}
            @foreach($logs as $log)
            <tr>
                <td>{{ $log['message'] }}</td>
                <td>{{ $log['by'] }}</td>
                <td>{{ $log['time'] }}</td>
            </tr>
            @endforeach
        </table>
    @else
        <p>No logs yet.</p>
    @endif
</div>

@endsection