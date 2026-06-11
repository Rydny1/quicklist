{{-- Master layout - every page extends this so the navbar is shared --}}
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
    {{-- search box, GET so the keyword shows up in the url --}}
    <form action="{{ route('listings.search') }}" method="GET" class="search-form">
        <input type="text" name="keyword" placeholder="{{ __('messages.search') }}...">
        <button type="submit">{{ __('messages.search') }}</button>
    </form>
    <div class="nav-links">
        {{-- language switcher --}}
        <a href="{{ route('lang.switch', 'lv') }}">LV</a> /
        <a href="{{ route('lang.switch', 'en') }}">EN</a>
        @auth
            {{-- only logged-in users see these --}}
            <a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a>
            @if(auth()->user()->isAdmin())
                {{-- and only admins get the admin panel link --}}
                <a href="{{ route('admin.index') }}">{{ __('messages.admin_panel') }}</a>
            @endif
            {{-- logout has to be a POST form because of CSRF protection --}}
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit">{{ __('messages.logout') }}</button>
            </form>
        @else
            {{-- guests just get login / register --}}
            <a href="{{ route('login') }}">{{ __('messages.login') }}</a>
            <a href="{{ route('register') }}">{{ __('messages.register') }}</a>
        @endauth
    </div>
</nav>

{{-- each child view drops its content in here --}}
<div class="container">
    @yield('content')
</div>

</body>
</html>