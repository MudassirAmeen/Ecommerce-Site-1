@extends('FrontEnd.layout')

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
        }

        .carausel_buttons {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }

        /* Zoom effect */
        .product-image {
            cursor: zoom-in;
            transition: transform 0.2s;
            transform-origin: center center;
            transform: scale(1);
        }

        .product-image.zoomed {
            transform: scale(2);
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
                                        alt="Product Image" class="img-fluid product-image">
                                </div>
                            @endforeach
                        </div>
                        <div class="carausel_buttons">
                            <a class="carousel-control-prev buy-now btn btn-sm btn-primary" href="#product-images"
                                role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next buy-now btn btn-sm btn-primary" href="#product-images"
                                role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="text-black">{{ $product->name }}</h2>
                    <p>{{ $product->long_description }}</p>
                    <p>Brand Name: {{ $product->user->name }}<br>Category: {{ $product->category->name }}</p>
                    <p><strong class="text-primary h4">${{ $product->price }}</strong></p>
                    <div class="mb-1 d-flex">
                        @foreach (json_decode($product->sizes, true) as $size)
                            <label for="{{ $size }}" class="d-flex mr-3 mb-3">
                                <span class="d-inline-block mr-2" style="top: -2px; position: relative;">
                                    <input type="radio" id="{{ $size }}" name="product_size">
                                </span>
                                <span class="d-inline-block text-black">{{ $size }}</span>
                            </label>
                        @endforeach
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <div class="input-group mb-3" style="max-width: 120px;">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-primary js-btn-minus" type="button">&minus;</button>
                                </div>
                                <input type="text" class="form-control text-center" value="1" placeholder=""
                                    aria-label="Example text with button addon" aria-describedby="button-addon1" name="quantity">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary js-btn-plus" type="button">&plus;</button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="buy-now btn btn-sm btn-primary">Add to Cart</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Add event listener to handle zoom effect
        var productImages = document.querySelectorAll('.product-image');

        productImages.forEach(function(image) {
            image.addEventListener('mousemove', function(event) {
                var boundingRect = image.getBoundingClientRect();
                var offsetX = event.clientX - boundingRect.left;
                var offsetY = event.clientY - boundingRect.top;
                var percentX = offsetX / boundingRect.width;
                var percentY = offsetY / boundingRect.height;

                image.style.transformOrigin = percentX * 100 + '% ' + percentY * 100 + '%';
                image.classList.add('zoomed');
            });

            image.addEventListener('mouseleave', function() {
                image.classList.remove('zoomed');
            });
        });
    </script>
@endsection
