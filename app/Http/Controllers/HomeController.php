<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use App\Models\User;

use App\Models\Food;
use App\Models\Cart;
use App\Models\Review;
use App\Models\order;
use App\Models\Book;



use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    public function my_home(Request $request)
{
    $categories = \App\Models\Category::all();

    \App\Models\Category::ensureDefaultCategories();
    $topCategories = \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

    $query = Food::query();

    if ($request->filled('category')) {
        $category = \App\Models\Category::with('children')->find($request->category);

        if ($category) {
            // If the selected category has children, show foods from the parent and its children.
            $childIds = $category->children->pluck('id')->toArray();
            // include the parent category id as well so items assigned to the main category appear
            $childIds[] = $category->id;
            $query->whereIn('category_id', $childIds);
        }
    }

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    $data = $query->with('reviews')->get();
    $city = $this->getDeliveryCity();

    return view('home.index', compact('data', 'city', 'topCategories', 'categories'));
}


    public function index()
    {
        if(Auth::id())
        {
            $usertype = Auth::user()->usertype;
            if($usertype=='user')
            {
                \App\Models\Category::ensureDefaultCategories();
                $topCategories = \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

                $data = Food::all();  
                $city = $this->getDeliveryCity(request());
                return view('home.index', compact('data', 'city', 'topCategories'));
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
    $request->validate([
        'qty' => 'required|integer|min:1|max:20',
    ]);

    if (!Auth::id())
    {
        if ($request->ajax()) {
            return response()->json(['error' => 'Please login first.'], 401);
        }
        return redirect('login');
    }

    $food = Food::find($id);

    $cart_title = $food->title;
    $cart_details = $food->detail;
    $cart_price = Str::remove('$', $food->price);
    $cart_image = $food->image;

    $data = new Cart;
    $data->title = $cart_title;
    $data->details = $cart_details;
    $data->price = $cart_price * $request->qty;
    $data->image = $cart_image;
    $data->quantity = $request->qty;
    $data->userid = Auth()->user()->id;
    $data->save();

    if ($request->ajax()) {
        return response()->json(['success' => true, 'message' => 'Added to cart!']);
    }

    return redirect()->back()->with('success', 'Added to cart!');
}


    public function my_cart()
{
    $user_id = Auth()->user()->id;
    $data = Cart::where('userid','=',$user_id)->get();
    $detectedCity = $this->getDeliveryCity();

    return view('home.my_cart', compact('data', 'detectedCity'));
}
    public function update_cart(Request $request, $id)
    {
        $request->validate(['action' => 'required|in:increase,decrease']);

        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $unitPrice = $cart->price / $cart->quantity;

        if ($request->action === 'increase') {
            $cart->quantity += 1;
        } elseif ($request->action === 'decrease' && $cart->quantity > 1) {
            $cart->quantity -= 1;
        }

        $cart->price = $unitPrice * $cart->quantity;
        $cart->save();

        return response()->json([
            'success'   => true,
            'quantity'  => $cart->quantity,
            'price'     => number_format($cart->price, 2),
            'cart_total'=> number_format(Cart::where('userid', Auth::id())->sum('price'), 2),
        ]);
    }

    public function add_review(Request $request, $foodId)
    {
        if (!Auth::id()) {
            return redirect('login');
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Review::create([
            'food_id' => $foodId,
            'user_id' => Auth::id(),
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Review submitted!');
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
        'delivery_city' => 'required|string',
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

            $Order->user_id = Auth::id();

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



    public function getDeliveryCity()
{
    try {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('http://ip-api.com/json');
        $data = json_decode($response->getBody(), true);
        return $data['city'] ?? null;
    } catch (\Exception $e) {
        return null;
    }
}

public function my_orders()
{
    $orders = order::where('user_id', Auth::id())->latest()->get();
    return view('home.my_orders', compact('orders'));
}




}
