<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickList</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<nav class="navbar">
    <a href="{{ route('home') }}" class="logo">QuickList</a>
    <form action="{{ route('listings.search') }}" method="GET" class="search-form">
        <input type="text" name="keyword" placeholder="{{ __('messages.search') }}...">
        <button type="submit">{{ __('messages.search') }}</button>
    </form>
    <div class="nav-links">
        <a href="{{ route('lang.switch', 'lv') }}">LV</a> /
        <a href="{{ route('lang.switch', 'en') }}">EN</a>
        @auth
            <a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.index') }}">{{ __('messages.admin_panel') }}</a>
            @endif
            {{-- logout needs POST because of CSRF --}}
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit">{{ __('messages.logout') }}</button>
            </form>
        @else
            <a href="{{ route('login') }}">{{ __('messages.login') }}</a>
            <a href="{{ route('register') }}">{{ __('messages.register') }}</a>
        @endauth
    </div>
</nav>

<div class="container">
    @yield('content')
</div>

</body>
</html>
