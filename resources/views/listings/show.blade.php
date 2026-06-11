@extends('layouts.app')

@section('content')

    <div class="listing-detail">
        <h1>{{ $listing->title }}</h1>
        <p class="meta">{{ $listing->category->name }} &bull; {{ $listing->location }} &bull; Posted by
            {{ $listing->user->name }}</p>

        @if ($listing->images->count() > 0)
            <div class="listing-images">
                @foreach ($listing->images as $image)
                    <img src="{{ asset('storage/' . $image->file_path) }}" alt="listing image">
                @endforeach
            </div>
        @endif

        <p class="price">€{{ number_format($listing->price, 2) }}</p>
        <p class="description">{{ $listing->description }}</p>

        @if ($listing->location)
            <div id="map" data-location="{{ $listing->location }}"></div>
        @endif

        @auth
            @if (auth()->id() === $listing->user_id || auth()->user()->isAdmin())
                <div class="listing-actions">
                    <a href="{{ route('listings.edit', $listing) }}" class="btn-red">{{ __('messages.edit') }}</a>
                    {{-- forms only do GET/POST so we fake the DELETE --}}
                    <form action="{{ route('listings.destroy', $listing) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-red"
                            onclick="return confirm('Are you sure?')">{{ __('messages.delete') }}</button>
                    </form>
                </div>
            @endif
        @endauth
    </div>

    <script>
        const mapDiv = document.getElementById('map');
        if (mapDiv) {
            const location = mapDiv.dataset.location;
            // free geocoding API from OpenStreetMap - converts address text to lat/lon
            fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(location)}&format=json`, {
                    headers: {
                        'Accept-Language': 'en',
                        'User-Agent': 'QuickList/1.0'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        mapDiv.innerHTML =
                            `<iframe width="100%" height="200" src="https://www.openstreetmap.org/export/embed.html?bbox=${lon-0.01},${lat-0.01},${lon+0.01},${lat+0.01}&marker=${lat},${lon}"></iframe>`;
                    }
                });
        }
    </script>

@endsection
