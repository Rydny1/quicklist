@extends('layouts.app')

@section('content')
<div class="auth-form">
    <h2>{{ __('messages.login') }}</h2>

    @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn-red">{{ __('messages.login') }}</button>
    </form>

    <p>Don't have an account? <a href="{{ route('register') }}">{{ __('messages.register') }}</a></p>
</div>
@endsection
