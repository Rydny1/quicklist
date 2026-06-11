{{-- The user's own dashboard - shows only the listings they posted --}}
@extends('layouts.app')

@section('content')

<div class="dashboard">
    <h2>{{ __('messages.dashboard') }}</h2>
    <a href="{{ route('listings.create') }}" class="btn-red">{{ __('messages.create_listing') }}</a>

    @if($listings->count() > 0)
        <div class="listings-grid">
            @foreach($listings as $listing)
            <div class="listing-card">
                {{-- thumbnail or placeholder, same idea as the homepage cards --}}
                @if($listing->images->count() > 0)
                    <img src="{{ asset('storage/'.$listing->images->first()->file_path) }}" alt="{{ $listing->title }}">
                @else
                    <div class="no-image">No image</div>
                @endif
                <div class="card-body">
                    <h3><a href="{{ route('listings.show', $listing) }}">{{ $listing->title }}</a></h3>
                    <p class="price">€{{ number_format($listing->price, 2) }}</p>
                    <p class="meta">{{ $listing->category->name }}</p>
                    {{-- since these are all the user's own ads, every card gets edit + delete --}}
                    <div class="card-actions">
                        <a href="{{ route('listings.edit', $listing) }}" class="btn-red">{{ __('messages.edit') }}</a>
                        <form action="{{ route('listings.destroy', $listing) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Are you sure?')">{{ __('messages.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        {{-- user hasn't posted anything yet --}}
        <p>{{ __('messages.no_listings') }}</p>
    @endif
</div>

@endsection