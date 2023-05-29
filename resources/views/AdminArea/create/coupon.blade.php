@extends('AdminArea.layout.layout')

@section('main')
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <form method="POST" action="{{ route('coupons.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Code</label>
                            <input type="text" name="code" id="code"
                                class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}"
                                required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Discount</label>
                            <input type="number" name="discount" id="discount"
                                class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount') }}"
                                required>
                            @error('discount')
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
