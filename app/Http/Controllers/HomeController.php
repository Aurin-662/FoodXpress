<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use App\Models\User;

use App\Models\Food;
use App\Models\Cart;
use App\Models\order;
use App\Models\Book;



use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    public function my_home()
    {

        $data = Food::all();    
        $city = $this->getDeliveryCity(request());
    
        return view('home.index', compact('data','city'));
    }


    public function index()
    {
        if(Auth::id())
        {
            $usertype = Auth::user()->usertype;
            if($usertype=='user')
            {
                $data = Food::all();  
                $city = $this->getDeliveryCity(request());
                return view('home.index', compact('data', 'city'));
            }
            else
            {

                $total_user = User::where('usertype','=','user')->count();
                $total_food = Food::count();
                $total_order = Order::count();
                $total_delivered = Order::where('delivery_status','=','delivered')->count();

                return view('admin.index', compact('total_user', 'total_food', 'total_order', 'total_delivered'));
            }
        }
    }

    
    
    public function add_cart(Request $request, $id)
{
    $request->validateWithBag('add_cart', [
        'qty' => 'required|integer|min:1|max:20',
    ]);

    if (Auth::id())
    {
        $food = Food::find($id);

        $cart_title = $food->title;
        $cart_details = $food->details;
        $cart_price = Str::remove('$', $food->price);
        $cart_image = $food->image;

        $data = new Cart;
        $data->title = $cart_title;
        $data->details = $cart_details;
        $data->price = $cart_price * $request->qty;
        $data->image = $cart_image;
        $data->quantity = $request->qty;
        $data->userid = Auth::user()->id;

        $data->save();

        return redirect()->back()->with('success', 'Added to cart!');
    }
    else
    {
        return redirect('login');
    }
}


    public function my_cart()
    {
        $user_id = Auth::user()->id;

        $data = Cart::where('userid','=',$user_id)->get();

        return view('home.my_cart', compact('data'));
    }

    public function remove_cart($id)
    {
        $data = Cart::find($id);
        $data->delete();
        return redirect()->back();
    }

    public function confirm_order(Request $request)
    {

        $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email',
        'phone'   => 'nullable|string|min:6',
        'address' => 'nullable|string|max:500',
    ]);


        $user_id = Auth::user()->id;

        
        $cart = Cart::where('userid','=',$user_id)->get();

        foreach($cart as $cart)
        {
            $Order = new order;
            $Order->name = $request->name;
            $Order->email = $request->email;
            $Order->phone = $request->phone;
            $Order->address = $request->address;

            $Order->title = $cart->title;
            $Order->quantity = $cart->quantity;

            $Order->price = $cart->price;
           
            
            $Order->image = $cart->image;
            

            $Order->save();

            $data = Cart::find($cart->id);

            $data->delete();

           
        }

           return redirect()->back()->with('success', 'Order placed successfully!');
    }


    public function book_table(Request $request)
    {

        $request->validateWithBag('book_table', [
        'phone'   => 'required|string|min:6',
        'n_guest' => 'required|integer|min:1|max:20',
        'date'    => 'required|date|after_or_equal:today',
        'time'    => 'required',
    ]);


        $data = new Book;

        $data->phone = $request->phone;
        $data->guest = $request->n_guest;
        $data->date = $request->date;
        $data->time = $request->time;

        $data->save();

        return redirect()->back()->with('success', 'Table booked successfully!')->withCookie(cookie('last_phone', $request->phone, 60 * 24 * 30));
    }



    public function getDeliveryCity(Request $request)
    {
        try {
            $client = new Client();
            $response = $client->get('http://ip-api.com/json');
            $data = json_decode($response->getBody(), true);
            return $data['city'] ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }



}
