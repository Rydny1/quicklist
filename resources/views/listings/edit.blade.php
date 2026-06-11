@extends('layouts.app')

@section('content')

    <div class="form-container">
        <h2>{{ __('messages.edit') }}</h2>

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('listings.update', $listing) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- forms only do GET/POST so we fake the PUT --}}
            @method('PUT')
            <div class="form-group">
                <label>{{ __('messages.title') }}</label>
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
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $listing->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Current images</label>
                @foreach ($listing->images as $image)
                    <div class="current-image">
                        <img src="{{ asset('storage/' . $image->file_path) }}" width="100">
                        {{-- fetch() can send DELETE directly, but still needs the CSRF token in headers --}}
                        <button type="button"
                            onclick="
                fetch('{{ route('images.destroy', $image) }}', {
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest'}
                }).then(() => this.closest('.current-image').remove())">Remove</button>
                    </div>
                @endforeach
            </div>
            <div class="form-group">
                <label>Add more images</label>
                <input type="file" name="images[]" multiple accept="image/*">
            </div>
            <button type="submit" class="btn-red">{{ __('messages.save') }}</button>
        </form>
    </div>

@endsection
