{{-- Homepage / search results / category page all reuse this view --}}
@extends('layouts.app')

@section('content')

<div class="filters">
    {{-- filter bar: category dropdown + price range, submits to the search route --}}
    <form action="{{ route('listings.search') }}" method="GET" class="filter-form">
        <select name="category_id">
            <option value="">{{ __('messages.category') }}</option>
            {{-- build an option for each category in the database --}}
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="number" name="min_price" placeholder="Min price">
        <input type="number" name="max_price" placeholder="Max price">
        <button type="submit" class="btn-red">{{ __('messages.search') }}</button>
    </form>

    {{-- only show the "post an ad" button to logged in users --}}
    @auth
        <a href="{{ route('listings.create') }}" class="btn-red">{{ __('messages.create_listing') }}</a>
    @endauth
</div>

{{-- if there are results show the grid, otherwise show a friendly message --}}
@if($listings->count() > 0)
    <div class="listings-grid">
        @foreach($listings as $listing)
        <div class="listing-card">
            {{-- show the first image as a thumbnail, or a placeholder if there's none --}}
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
    {{-- pagination links (we paginate 12 per page in the controller) --}}
    <div class="pagination">
        {{ $listings->links() }}
    </div>
@else
    <p>{{ __('messages.no_listings') }}</p>
@endif

@endsection