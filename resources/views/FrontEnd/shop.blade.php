@extends('FrontEnd.layout')

@section('main')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('FrontEnd') }}">Home</a> <span class="mx-2 mb-0">/</span> <strong
                        class="text-black">Shop</strong></div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">

            <div class="row mb-5">
                <div class="col-md-9 order-2">

                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <div class="float-md-left mb-4">
                                <h2 class="text-black h5">Shop All</h2>
                            </div>
                            <div class="d-flex">
                                <div class="dropdown mr-1 ml-md-auto">
                                    {{--  <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                        id="dropdownMenuOffset" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Latest
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                                        <a class="dropdown-item" href="#">Men</a>
                                        <a class="dropdown-item" href="#">Women</a>
                                        <a class="dropdown-item" href="#">Children</a>
                                    </div>  --}}
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                        id="dropdownMenuReference" data-toggle="dropdown">Reference</button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                                        <a class="dropdown-item"
                                            href="{{ route('shopfilter-sort', ['sort' => 'relevance']) }}">Relevance</a>
                                        <a class="dropdown-item" href="{{ route('shopfilter-sort', ['sort' => 'name_asc']) }}">Name,
                                            A to Z</a>
                                        <a class="dropdown-item" href="{{ route('shopfilter-sort', ['sort' => 'name_desc']) }}">Name,
                                            Z to A</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('shopfilter-sort', ['sort' => 'price_asc']) }}">Price,
                                            low to high</a>
                                        <a class="dropdown-item" href="{{ route('shopfilter-sort', ['sort' => 'price_desc']) }}">Price,
                                            high to low</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">

                        @foreach ($products as $product)
                            @php
                                $decodedImages = json_decode($product->images);
                                $firstImage = isset($decodedImages[0]) ? $decodedImages[0] : null;
                                $imageUrl = str_replace('public/', '', $firstImage);
                                $shortDescription = \Illuminate\Support\Str::limit($product->short_description, 20, '...');
                                $name = \Illuminate\Support\Str::limit($product->name, 10, '...');
                            @endphp
                            <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                                <div class="block-4 text-center border">
                                    <figure class="image">
                                        @if ($imageUrl)
                                            <img src="{{ asset('storage/images/' . $imageUrl) }}" alt="Product Image"
                                                class="img-fluid">
                                        @else
                                            No Image
                                        @endif
                                    </figure>
                                    <div class="block-4-text p-4">
                                        <h3><a href="{{route('single', $product->slug)}}">{{ $name }}</a></h3>
                                        <p class="mb-0">{{ $shortDescription }}</p>
                                        <p class="text-primary font-weight-bold">${{ $product->price }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>
                    {{ $products->links() }}

                </div>

                <div class="col-md-3 order-1 mb-5 mb-md-0">
                    <div class="border p-4 rounded mb-4">
                        <h3 class="mb-3 h6 text-uppercase text-black d-block">Categories</h3>
                        <ul class="list-unstyled mb-0">

                            @foreach ($categories as $category)
                                @if ($category->products_count != 0)
                                    <li class="mb-1">
                                        <a href="#" class="d-flex">
                                            <span>{{ $category->name }}</span>
                                            <span class="text-black ml-auto">({{ $category->products_count }})</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach

                        </ul>
                    </div>

                    <div class="border p-4 rounded mb-4">
                        {{--  <div class="mb-4">
                            <h3 class="mb-3 h6 text-uppercase text-black d-block">Filter by Price</h3>
                            <div id="slider-range" class="border-primary"></div>
                            <input type="text" name="text" id="amount" class="form-control border-0 pl-0 bg-white"
                                disabled=""/>
                        </div>  --}}

                        <div class="mb-4">
                            <h3 class="mb-3 h6 text-uppercase text-black d-block">Size</h3>
                            <form action="{{ route('shopfilter-sort') }}" method="POST">
                                @csrf
                                @foreach ($sizeCounts as $size => $count)
                                    <label for="{{ $size . $count }}" class="d-flex">
                                        <input type="checkbox" id="{{ $size . $count }}" name="sizes[]"
                                            value="{{ $size }}" class="mr-2 mt-1">
                                        <span class="text-black">{{ $size }} ({{ $count }})</span>
                                    </label>
                                @endforeach
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Filter</button>
                            </form>


                        </div>

                        {{--  <div class="mb-4">
                            <h3 class="mb-3 h6 text-uppercase text-black d-block">Color</h3>
                            <a href="#" class="d-flex color-item align-items-center">
                                <span class="bg-danger color d-inline-block rounded-circle mr-2"></span> <span
                                    class="text-black">Red (2,429)</span>
                            </a>
                            <a href="#" class="d-flex color-item align-items-center">
                                <span class="bg-success color d-inline-block rounded-circle mr-2"></span> <span
                                    class="text-black">Green (2,298)</span>
                            </a>
                            <a href="#" class="d-flex color-item align-items-center">
                                <span class="bg-info color d-inline-block rounded-circle mr-2"></span> <span
                                    class="text-black">Blue (1,075)</span>
                            </a>
                            <a href="#" class="d-flex color-item align-items-center">
                                <span class="bg-primary color d-inline-block rounded-circle mr-2"></span> <span
                                    class="text-black">Purple (1,075)</span>
                            </a>
                        </div>  --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
