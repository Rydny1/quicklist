@extends('layouts.app')

@section('content')

<div class="form-container">
    <h2>{{ __('messages.create_listing') }}</h2>

    @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- enctype needed for file uploads --}}
    <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>{{ __('messages.title') }}</label>
            <input type="text" name="title" value="{{ old('title') }}" required>
        </div>
        <div class="form-group">
            <label>{{ __('messages.description') }}</label>
            <textarea name="description" rows="5">{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
            <label>{{ __('messages.price') }} (€)</label>
            <input type="number" step="0.01" name="price" value="{{ old('price') }}">
        </div>
        <div class="form-group">
            <label>{{ __('messages.location') }}</label>
            <input type="text" name="location" value="{{ old('location') }}">
        </div>
        <div class="form-group">
            <label>{{ __('messages.category') }}</label>
            <select name="category_id" required>
                <option value="">-- select --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>{{ __('messages.images') }}</label>
            <input type="file" name="images[]" multiple accept="image/*">
        </div>
        <button type="submit" class="btn-red">{{ __('messages.save') }}</button>
    </form>
</div>

@endsection
