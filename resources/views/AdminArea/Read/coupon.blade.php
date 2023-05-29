@extends('AdminArea.layout.layout')

@section('main')
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Code</th>
                <th>Discount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->discount}}</td>
                    <td style="width: 200px">
                        <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger "
                                onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach

            @if ($coupons->count() == 0)
                <tr>
                    <td colspan="2" class="bg-primary text-light text-center">
                        nothing found
                    </td>
                </tr>
            @endif

        </tbody>
    </table>
@endsection
