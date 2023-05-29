@extends('AdminArea.layout.layout')

@section('main')
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Long Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                @php
                    $decodedImages = json_decode($product->images);
                    $firstImage = isset($decodedImages[0]) ? $decodedImages[0] : null;
                    $imageUrl = str_replace('public/', '', $firstImage);
                @endphp
                {{--  <a href="{{ route('product.show', ['product' => $product->id]) }}">  --}}
                    <tr onclick="window.location='{{ route('product.show', ['product' => $product->id]) }}';" style="cursor: pointer">
                        <td>
                            @if ($imageUrl)
                                <img src="{{ asset('storage/images/' . $imageUrl) }}" alt="Product Image"
                                    style="width: 78px; height: 55px;">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>{{$product->name}}</td>
                        <td>{{ \Illuminate\Support\Str::limit($product->long_description, 150, '...') }}</td>
                        <td style="width: 200px">
                            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary ">Edit</a>
                            <form action="{{ route('product.destroy', $product->id) }}" method="POST"
                                style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger "
                                    onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                {{--  </a>  --}}
            @endforeach
            
            @if ($products->count() == 0)
                <tr>
                    <td colspan="4" class="bg-primary text-light text-center">
                        nothing found
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
