@extends('layouts.app')

@section('content')
<div class="auth-form">
    <h2>{{ __('messages.register') }}</h2>

    @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn-red">{{ __('messages.register') }}</button>
    </form>

    <p>Already have an account? <a href="{{ route('login') }}">{{ __('messages.login') }}</a></p>
</div>
@endsection
