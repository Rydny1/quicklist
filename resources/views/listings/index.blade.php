@extends('layouts.app')

@section('content')

<div class="filters">
    <form action="{{ route('listings.search') }}" method="GET" class="filter-form">
        <select name="category_id">
            <option value="">{{ __('messages.category') }}</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="number" name="min_price" placeholder="Min price">
        <input type="number" name="max_price" placeholder="Max price">
        <button type="submit" class="btn-red">{{ __('messages.search') }}</button>
    </form>

    @auth
        <a href="{{ route('listings.create') }}" class="btn-red">{{ __('messages.create_listing') }}</a>
    @endauth
</div>

@if($listings->count() > 0)
    <div class="listings-grid">
        @foreach($listings as $listing)
        <div class="listing-card">
            @if($listing->images->count() > 0)
                <img src="{{ asset('storage/'.$listing->images->first()->file_path) }}" alt="{{ $listing->title }}">
            @else
                <div class="no-image">No image</div>
            @endif
            <div class="card-body">
                <h3><a href="{{ route('listings.show', $listing) }}">{{ $listing->title }}</a></h3>
                <p class="price">€{{ number_format($listing->price, 2) }}</p>
                <p class="meta">{{ $listing->category->name }} &bull; {{ $listing->location }}</p>
            </div>
        </div>
        @endforeach
    </div>
    <div class="pagination">
        {{ $listings->links() }}
    </div>
@else
    <p>{{ __('messages.no_listings') }}</p>
@endif

@endsection
