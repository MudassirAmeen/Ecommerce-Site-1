@extends('FrontEnd.layout')

@section('main')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('FrontEnd') }}">Home</a> <span class="mx-2 mb-0">/</span> <strong
                        class="text-black">Cart</strong></div>
            </div>
        </div>
    </div>
    @if (session()->has('cartItems') && count(session()->get('cartItems')) > 0)
        <div class="site-section">
            <div class="container">
                <div class="row mb-5">
                    <form class="col-md-12" method="post">
                        <div class="site-blocks-table">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="product-thumbnail">Image</th>
                                        <th class="product-name">Product</th>
                                        <th class="product-price">Price</th>
                                        <th class="product-quantity">Quantity</th>
                                        <th class="product-total">Total</th>
                                        <th class="product-remove">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                        <tr>
                                            @php
                                                $decodedImages = json_decode($item->images);
                                                $firstImage = isset($decodedImages[0]) ? $decodedImages[0] : null;
                                                $imageUrl = str_replace('public/', '', $firstImage);
                                            @endphp
                                            <td class="product-thumbnail">
                                                @if ($imageUrl)
                                                    <img src="{{ asset('storage/images/' . $imageUrl) }}"
                                                        alt="Product Image" class="img-fluid">
                                                @else
                                                    No Image
                                                @endif
                                            </td>
                                            <td class="product-name">
                                                <h2 class="h5 text-black"><a href="{{route('single', $item->slug)}}" style="color: inherit">{{ $item->name }}</a></h2>
                                            </td>
                                            <td>${{ $item->price }}</td>
                                            <td>
                                                <div class="input-group mb-3" style="max-width: 120px;">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-outline-primary js-btn-minus"
                                                            type="button">&minus;</button>
                                                    </div>
                                                    <input type="text" class="form-control text-center quantity-input"
                                                        value="{{ $item->quantity }}" data-item-id="{{ $item->id }}"
                                                        data-item-name="{{ $item->name }}" min="1" placeholder=""
                                                        aria-label="Example text with button addon"
                                                        aria-describedby="button-addon1" id="quantityOfProduct" disabled>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-primary js-btn-plus"
                                                            type="button">&plus;</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$
                                                <span
                                                    id="{{ $item->id . $item->name }}">{{ $item->price * $item->quantity }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('cart.remove', ['id' => $item->id]) }}"
                                                    class="btn btn-primary btn-sm">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <a href="{{route('shopindex')}}" class="btn btn-outline-primary btn-sm btn-block">Continue Shopping</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="text-black h4" for="coupon">Coupon</label>
                                <p>Enter your coupon code if you have one.</p>
                            </div>
                            <form action="{{route('checkCoupon')}}" method="POST" style="display:contents">
                                @csrf
                                <div class="col-md-8 mb-3 mb-md-0">
                                    <input type="text" class="form-control py-3" id="coupon" name='code' placeholder="Coupon Code">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary btn-sm">Apply Coupon</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6 pl-5">
                        <div class="row justify-content-end">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-12 text-right border-bottom mb-5">
                                        <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <span class="text-black">Total</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <strong class="text-black" id="totalPrice">${{ session()->has('totalPrice') ? session()->get('totalPrice') : 'Session Not Created.'}}</strong>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{route('checkout')}}" style=""><button class="btn btn-primary btn-lg py-3 btn-block" >Proceed To Checkout</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="site-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        {{--  <span class="icon-check_circle display-3 text-success"></span>  --}}
                        <h2 class="display-3 text-black">Empty</h2>
                        <p class="lead mb-5">Your Cart Is Empty. Please Take a look on Shop Page</p>
                        <p><a href="{{ route('shopindex') }}" class="btn btn-sm btn-primary">Back to shop</a></p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('footer')
    <script>
        document.querySelectorAll('.js-btn-minus, .js-btn-plus').forEach(function(button) {
            button.addEventListener('click', function() {
                var input = this.parentNode.parentNode.querySelector('.quantity-input');
                updateCart(input);
            });
        });

        function updateCart(input) {
            var itemId = input.getAttribute('data-item-id');
            var itemName = input.getAttribute('data-item-name');
            var form = input.closest('form');
            var quantityInput = document.createElement('input');
            quantityInput.setAttribute('type', 'hidden');
            quantityInput.setAttribute('name', 'quantity');
            form.appendChild(quantityInput);

            // Wait for a brief moment before capturing the updated value
            setTimeout(function() {
                var quantity = input.value;
                quantityInput.setAttribute('value', quantity);

                fetch('/cart/update/' + itemId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token if required
                        },
                        body: JSON.stringify({
                            quantity: quantity
                        })
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        // Update the view with the response
                        var updatedQuantity = data.quantity;
                        var updatedPrice = data.total_price;

                        // Update the respective elements in the view
                        var quantityElement = itemId;
                        quantityElement.value = updatedQuantity;

                        var priceElement = document.getElementById(itemId + itemName);
                        priceElement.innerText = updatedPrice;

                        var totalPriceElement = document.getElementById('totalPrice');
                        totalPriceElement.innerText = '$' + data.totalPriceOfAllProductInCart;
                    });
            }, 100);
        }
    </script>
@endsection
