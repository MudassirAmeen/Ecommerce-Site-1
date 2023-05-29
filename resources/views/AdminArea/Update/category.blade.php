@extends('AdminArea.layout.layout')

@section('main')
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <form method="POST" action="{{ route('category.update', ['category' => $category->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') ? old('name') : $category->name }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
