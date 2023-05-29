@extends('AdminArea.layout.layout')

@section('header')
    <style>
        .image-preview {
            white-space: nowrap;
            overflow-x: auto;
            margin: 10px 0px;
        }

        .preview-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            display: inline-block;
            margin-right: 10px;
        }
    </style>
@endsection

@section('main')
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="price">Price</label>
                                <input type="number" name="price" id="price"
                                    class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}"
                                    required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputState">Category</label>
                                <select id="inputState" class="form-control" name="category_id">
                                    @foreach ($categories as $category)
                                        <option value='{{$category->id}}'>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="images">Images</label>
                            <input type="file" name="images[]" id="images"
                                class="form-control-file @error('images') is-invalid @enderror" multiple>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="image-preview" class="image-preview"></div>

                        <script>
                            document.getElementById('images').addEventListener('change', function(event) {
                                var previewContainer = document.getElementById('image-preview');
                                previewContainer.innerHTML = ''; // Clear previous previews

                                var files = event.target.files;
                                for (var i = 0; i < files.length; i++) {
                                    var file = files[i];
                                    var reader = new FileReader();

                                    reader.onload = function(e) {
                                        var image = document.createElement('img');
                                        image.src = e.target.result;
                                        image.classList.add('preview-image');

                                        previewContainer.appendChild(image);
                                    }

                                    reader.readAsDataURL(file);
                                }
                            });
                        </script>

                        <div class="form-group">
                            <label for="short_description">Short Description</label>
                            <textarea name="short_description" id="short_description"
                                class="form-control @error('short_description') is-invalid @enderror" required>{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="long_description">Long Description</label>
                            <textarea name="long_description" rows='5' id="long_description"
                                class="form-control @error('long_description') is-invalid @enderror" required>{{ old('long_description') }}</textarea>
                            @error('long_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sizes">Sizes (Press the Ctrl Button to select multiple sizes.)</label>
                            <select name="sizes[]" id="sizes" class="form-control @error('sizes') is-invalid @enderror"
                                multiple>
                                <option value="extra_large"
                                    {{ in_array('extra_large', old('sizes', [])) ? 'selected' : '' }}>Extra Large</option>
                                <option value="large" {{ in_array('large', old('sizes', [])) ? 'selected' : '' }}>Large
                                </option>
                                <option value="medium" {{ in_array('medium', old('sizes', [])) ? 'selected' : '' }}>Medium
                                </option>
                                <option value="small" {{ in_array('small', old('sizes', [])) ? 'selected' : '' }}>Small
                                </option>
                            </select>
                            @error('sizes')
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
