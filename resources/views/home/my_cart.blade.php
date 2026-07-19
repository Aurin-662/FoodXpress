<!DOCTYPE html>
<html lang="en">
<head>
	@include('home.css')
<style>
    table{
        border: 1px solid skyblue; 
        margin: 40px;
        padding: 40px;
    }
    th{
        background-color: red;
        text-align: center;
        color: white;
        padding: 10px;
        font-weight: bold;
    }
    td{
        color: white;
        padding: 10px;
    }
    .div_center{
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 50px;
        
    }
    label{
        display: inline-block;
        width: 200px;
        color: white;
    }
    .div_deg{
        padding: 20px;
    }



</style>

</head>
<body data-spy="scroll" data-target=".navbar" data-offset="40" id="home">
    
    <nav class="custom-navbar navbar navbar-expand-lg navbar-dark fixed-top" data-spy="affix" data-offset-top="10">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#gallary">Gallary</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#book-table">Book-Table</a>
                </li>
            </ul>
            <a class="navbar-brand m-auto" href="{{ url('/') }}">
                <img src="assets/imgs/logo.svg" class="brand-img" alt="">
                <span class="brand-txt">Food Hut</span>
            </a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#blog">Blog<span class="sr-only">(current)</span></a>
                </li>

                @if (Route::has('login'))

                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('my_cart') }}">Cart</a>
                </li>
                
                

        
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('my_orders') }}">My Orders</a>
                </li>




                <form action="{{ route('logout') }}" method="POST">
                       @csrf
                    <input class="btn btn-primary ml-xl-4" type="submit" value="Logout">
                </form>

                @else



                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                </li>

                @endauth
                @endif

            
            </ul>
        </div>
    </nav>
</br></br></br>



    <div id="gallary" class="text-center bg-dark text-light has-height-md middle-items wow fadeIn">


    @if(session('success'))
    </br></br></br>
        <div class="alert alert-success text-center" style="margin: 0 40px; border-radius: 8px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    </br></br></br>
        <div class="alert alert-danger text-center" style="margin: 0 40px; border-radius: 8px;">{{ session('error') }}</div>
    @endif


        @if($data->count() > 0)
        </br> </br> </br> 
        <table>
            <tr>

                <th>Food Title</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Remove</th>
            </tr>

         @foreach($data as $item)
            <tr>
             
                <td>{{$item->title}}</td>
                <td>${{$item->price}}</td>
                <td>{{$item->quantity}}</td>
                <td>
                    <img width="150" src="food_img/{{$item->image}}" alt="">
                </td>
                <td>
                    <a class="btn btn-danger" onclick="return confirm('Are you sure to remove this item from cart?')" href="{{ url('remove_cart', $item->id) }}">Delete</a>
                </td>
            </tr>
        @endforeach

        </table>
        <h3><b>Total Price: ${{ $data->sum('price') }}</b></h3>
        
        @else
        <h2 style="color: white; padding: 40px;">Your cart is empty!</h2>
        @endif

    </div>


    </div>

    <div class="div_center">

        <form action="{{url('confirm_order')}}" method="POST">
        @csrf

        @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


        <div class="div_deg">
            <label for="">Name</label>
            <input type="text" name="name" value="{{ Auth::user()->name }}" required>
        </div>

        <div class="div_deg">
            <label for="">Email</label>
            <input type="email" name="email" value="{{ Auth::user()->email }}" required>
        </div>

        <div class="div_deg">
            <label for="">Phone</label>
            <input type="number" name="phone" value="{{ Auth::user()->phone ?? '' }}">
        </div>
        <div class="div_deg">
            <label for="">Address</label>
            <input type="text" name="address" value="{{ Auth::user()->address ?? '' }}">
        </div>



        <div class="div_deg">
            <label for="">Delivery City</label>
            <select name="delivery_city" required style="padding:8px; border-radius:5px; width: 250px;">
                <option value="">-- Select city --</option>
                @foreach(['Dhaka','Chittagong','Khulna','Rajshahi','Sylhet','Barisal','Rangpur','Mymensingh'] as $cityOption)
                    <option value="{{ $cityOption }}" {{ old('delivery_city', $detectedCity) == $cityOption ? 'selected' : '' }}>
                        {{ $cityOption }}
                    </option>
                @endforeach
            </select>
            @if($detectedCity)
                <br><small style="color:#ccc;">Detected your location as {{ $detectedCity }} — change it if ordering delivery to a different city.</small>
            @endif
        </div>


        <div class="div_deg">
           
            <input type="submit" value="Confirm Order" class="btn btn-info">
        </div>





        </form>



    </div>
    
</body>
</html>
