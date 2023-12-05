@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Products</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->has('stripe_error'))
    <div class="alert alert-danger">
        {{ $errors->first('stripe_error') }}
    </div>
@endif

    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">Price: ${{ $product->price }}</p>
                        <p class="card-text">{{ $product->description }}</p>
                        <a href="{{ route('product.buy', $product->id) }}" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-12">No products found.</p>
        @endforelse
    </div>
    <div class="d-flex mt-4">
        {{ $products->links() }}
    </div>
@endsection
