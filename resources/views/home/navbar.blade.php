<!-- Navbar -->
<nav class="custom-navbar navbar navbar-expand-lg navbar-dark fixed-top" data-spy="affix" data-offset-top="10">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#home">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#about">About</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#gallary">Gallery</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#book-table">Book Table</a></li>
        </ul>
        <a class="navbar-brand m-auto" href="{{ url('/') }}">
            <img src="{{ asset('assets/imgs/logo.svg') }}" class="brand-img" alt="FoodXpress logo">
            <span class="brand-txt">FoodXpress</span>
        </a>
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#blog">Menu</a></li>
            @if (Route::has('login'))
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ url('my_cart') }}">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('my_orders') }}">My Orders</a></li>
                    <form action="{{ route('logout') }}" method="POST" class="form-inline ml-xl-4">
                        @csrf
                        <input class="btn btn-primary" type="submit" value="Logout">
                    </form>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @endauth
            @endif
        </ul>
    </div>
</nav>
<x-flash-message />