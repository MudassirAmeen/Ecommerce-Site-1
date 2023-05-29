@extends('AdminArea.layout.layout')

@section('header')
    <style>
        .carousel-container {
            position: relative;
        }

        .carousel-control-prev,
        .carousel-control-next {
            position: relative;
            background: #5a50e5;
            opacity: 1;
            height: 20px;

            {{--  bottom: -40px;  --}}
            /* Adjust the spacing as needed */
        }
        .carausel_buttons{
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }
    </style>
@endsection
@section('main')
    <div class="site-section">
        <div class="container">
            <div class="row">
                @php
                    $decodedImages = json_decode($product->images);
                    $firstImage = isset($decodedImages[0]) ? $decodedImages[0] : null;
                    $imageUrl = str_replace('public/', '', $firstImage);
                @endphp
                <div class="col-md-6">
                    <div id="product-images" class="carousel slide" data-ride="carousel">
                        
                        <div class="carousel-inner">
                            @foreach ($decodedImages as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/images/' . str_replace('public/', '', $image)) }}"
                                        alt="Product Image" class="img-fluid">
                                </div>
                            @endforeach
                        </div>
                        <div class="carausel_buttons">
                            <a class="carousel-control-prev buy-now btn btn-sm btn-primary" href="#product-images" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next buy-now btn btn-sm btn-primary" href="#product-images" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="text-black">{{ $product->name }}</h2>
                    <p>{{ $product->long_description }}</p>
                    <p>Brand Name: {{$product->user->name}}<br>Category: {{$product->category->name}}</p>
                    <p><strong class="text-primary h4">${{ $product->price }}</strong></p>
                    <div class="mb-1 d-flex">
                        @foreach (json_decode($product->sizes, true) as $size)
                            <label for="{{ $size }}" class="d-flex mr-3 mb-3">
                                <span class="d-inline-block mr-2" style="top: -2px; position: relative;"><input
                                        type="radio" id="{{ $size }}" name="product_size"></span> <span
                                    class="d-inline-block text-black">{{ $size }}</span>
                            </label>
                        @endforeach
                    </div>

                    <p><a href="{{ route('product.edit', ['product' => $product->id]) }}"
                            class="buy-now btn btn-sm btn-primary">Edit</a></p>

                </div>
            </div>
        </div>
    </div>
    {{--  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#product-images .carousel-item').first().addClass('active');
        });
    </script>  --}}
@endsection

@section('footer')
    <script src="{{ asset('FrontEnd/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('FrontEnd/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('FrontEnd/js/popper.min.js') }}"></script>
    <script src="{{ asset('FrontEnd/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('FrontEnd/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('FrontEnd/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('FrontEnd/js/aos.js') }}"></script>
    <script src="{{ asset('FrontEnd/js/main.js') }}"></script>
@endsection
