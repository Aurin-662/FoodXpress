<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.css')
</head>
<body data-spy="scroll" data-target=".navbar" data-offset="40" id="home">

    @include('home.navbar')

    <div class="container-fluid bg-dark text-light py-5" style="min-height: 60vh;">
</br> </br> </br> <h2 class="text-center mb-5">My Orders</h2>

        @if($orders->count() > 0)
        <div class="row justify-content-center">
            @php
                $statusColors = [
                    'In progress' => '#f0ad4e',
                    'On the way'  => '#5bc0de',
                    'Delivered'   => '#28a745',
                    'Canceled'    => '#dc3545',
                ];
            @endphp

            @foreach($orders as $order)
            <div class="col-md-8 mb-3">
                <div class="d-flex align-items-center justify-content-between p-3" style="background:#2a2a2a; border-radius:8px;">
                    <img width="70" src="{{ asset('food_img/'.$order->image) }}" alt="{{ $order->title }}" style="border-radius:6px;">
                    <div class="flex-grow-1 px-3 text-left">
                        <h5 class="mb-1">{{ $order->title }}</h5>
                        <small>Qty: {{ $order->quantity }} &nbsp;|&nbsp; ${{ $order->price }}</small><br>
                        <small class="text-muted">Ordered on {{ $order->created_at->format('d M Y, h:i A') }}</small>
                    </div>
                    <span style="background: {{ $statusColors[$order->delivery_status] ?? '#999' }}; color:white; padding:6px 14px; border-radius:20px; font-size: 0.9rem; white-space: nowrap;">
                        {{ $order->delivery_status }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <h4 class="text-center text-muted">You haven't placed any orders yet.</h4>
        @endif
    </div>

    @include('home.footer')
</body>
</html>