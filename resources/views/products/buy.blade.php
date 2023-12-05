@extends('layouts.app')

@section('content')
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
    <h1>{{ $product->name }}</h1>
    <p>Price: ${{ $product->price }}</p>

    <form action="{{ route('stripe.charge') }}" method="post" id="payment-form">
        @csrf

        <div class="form-group">
            <label for="card-holder-name">Card Holder Name</label>
            <input type="text" id="card-holder-name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="card-element">Credit or debit card</label>
            <div id="card-element" class="form-control">
                <!-- A Stripe Element will be inserted here. -->
            </div>
            <div id="card-errors" role="alert"></div>
        </div>
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <button type="submit" class="btn btn-primary">Pay Now</button>

    </form>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();

        // Create an instance of the card Element.
        const card = elements.create('card', {
            style: {
                base: {
                    iconColor: '#666',
                    color: '#333',
                    lineHeight: '1.42857143',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSize: '15px',
                },
            },
            hidePostalCode: true
        });

        // Add an instance of the card Element into the `card-element` div.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function (event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        const form = document.getElementById('payment-form');
        const cardHolderName = document.getElementById('card-holder-name');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createPaymentMethod({
                type: 'card',
                card: card,
                billing_details: {
                    name: cardHolderName.value
                }
            }).then(function (result) {
                if (result.error) {
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    stripeTokenHandler(result.paymentMethod);
                }
            });
        });

        function stripeTokenHandler(paymentMethod) {
            // Insert the token ID into the form so it gets submitted to the server.
            const form = document.getElementById('payment-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method');
            hiddenInput.setAttribute('value', paymentMethod.id);
            form.appendChild(hiddenInput);
            form.submit();
        }
    </script>
@endsection
