{{-- Edit form - basically the create form but pre-filled with the listing's data --}}
@extends('layouts.app')

@section('content')

<div class="form-container">
    <h2>{{ __('messages.edit') }}</h2>

    @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('listings.update', $listing) }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- html forms can't send PUT, so we spoof it with @method --}}
        @method('PUT')
        <div class="form-group">
            <label>{{ __('messages.title') }}</label>
            {{-- pre-fill with the current value so the user can tweak it --}}
            <input type="text" name="title" value="{{ $listing->title }}" required>
        </div>
        <div class="form-group">
            <label>{{ __('messages.description') }}</label>
            <textarea name="description" rows="5">{{ $listing->description }}</textarea>
        </div>
        <div class="form-group">
            <label>{{ __('messages.price') }} (€)</label>
            <input type="number" step="0.01" name="price" value="{{ $listing->price }}">
        </div>
        <div class="form-group">
            <label>{{ __('messages.location') }}</label>
            <input type="text" name="location" value="{{ $listing->location }}">
        </div>
        <div class="form-group">
            <label>{{ __('messages.category') }}</label>
            <select name="category_id" required>
                {{-- mark the listing's current category as selected --}}
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $listing->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Current images</label>
            {{-- show each existing image with its own little remove button --}}
            @foreach($listing->images as $image)
                <div class="current-image">
                    <img src="{{ asset('storage/'.$image->file_path) }}" width="100">
                    {{-- separate form so removing an image doesn't submit the whole edit --}}
                    <form action="{{ route('images.destroy', $image) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Remove</button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="form-group">
            <label>Add more images</label>
            {{-- any files added here get appended to the listing on update --}}
            <input type="file" name="images[]" multiple accept="image/*">
        </div>
        <button type="submit" class="btn-red">{{ __('messages.save') }}</button>
    </form>
</div>

@endsection