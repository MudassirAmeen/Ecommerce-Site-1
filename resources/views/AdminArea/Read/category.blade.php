@extends('AdminArea.layout.layout')

@section('main')
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td style="width: 200px">
                        <a href="{{ route('category.edit', $category->id) }}" class="btn btn-primary ">Edit</a>
                        <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger "
                                onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach

            @if ($categories->count() == 0)
                <tr>
                    <td colspan="2" class="bg-primary text-light text-center">
                        nothing found
                    </td>
                </tr>
            @endif

        </tbody>
    </table>
@endsection
