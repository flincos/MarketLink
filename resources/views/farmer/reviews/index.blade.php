@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Customer Reviews</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($reviews->isEmpty())

        <div class="alert alert-info">
            No customer reviews yet.
        </div>

    @else

        @foreach($reviews as $review)

            <div class="card mb-4">

                <div class="card-body">

                    <h5>
                        {{ $review->product->name ?? 'Product' }}
                    </h5>

                    <p class="mb-1">
                        <strong>Customer:</strong>
                        {{ $review->customer->name ?? 'N/A' }}
                    </p>

                    <p class="mb-1">
                        <strong>Rating:</strong>
                        {{ $review->rating }}/5
                    </p>

                    <p>
                        <strong>Review:</strong>
                        {{ $review->comment }}
                    </p>

                    @if($review->farmer_response)

                        <div class="alert alert-secondary">
                            <strong>Your Response:</strong>
                            {{ $review->farmer_response }}
                        </div>

                    @else

                        <form method="POST"
                              action="{{ route('farmer.reviews.respond', $review) }}">

                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="farmer_response_{{ $review->id }}"
                                       class="form-label">
                                    Respond to Customer
                                </label>

                                <textarea
                                    name="farmer_response"
                                    id="farmer_response_{{ $review->id }}"
                                    class="form-control"
                                    rows="3"
                                    maxlength="1000"
                                    required
                                ></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Submit Response
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        @endforeach

    @endif

</div>
@endsection