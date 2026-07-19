<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Food;
use App\Models\Review;

use App\Models\Category;
use App\Models\order;
use App\Models\Book;

class AdminController extends Controller
{
    public function add_food()
    {
        Category::ensureDefaultCategories();
        $topCategories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('admin.add_food', compact('topCategories'));
    }
    
    public function upload_food(Request $request)
{
    Category::ensureDefaultCategories();

    $request->validate([
        'title'   => 'required|string|max:255',
        'details' => 'required|string',
        'price'   => 'required|numeric|min:0',
        'img'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'category_id' => 'required|exists:categories,id',
    ]);

    $data = new Food;
    $data->title = $request->title;
    $data->detail = $request->details;
    $data->price = $request->price;
$data->category_id = $request->category_id;

    $image = $request->img;
    $filename = time().'.'.$image->getClientOriginalExtension();
    $request->img->move('food_img', $filename);
    $data->image = $filename;
    $data->save();

    return redirect()->back()->with('success', 'Food item added successfully!');
}



    public function view_food()
    {
        $data = Food::all();
        return view('admin.show_food', compact('data'));
    }
    public function delete_food($id)
    {
        $data = Food::find($id);
        $data->delete();
        return redirect()->back();
    }
    public function update_food($id)
    {
        Category::ensureDefaultCategories();
        $food = Food::find($id);
        $topCategories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('admin.update_food', compact('food', 'topCategories'));
    }



   public function edit_food(Request $request, $id)
{
    $request->validate([
        'title'   => 'required|string|max:255',
        'details' => 'required|string',
        'price'   => 'required|numeric|min:0',
        'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'category_id' => 'nullable|exists:categories,id',
    ]);

    $data = Food::find($id);
    $data->title = $request->title;
    $data->detail = $request->details;
    $data->price = $request->price;
    $data->category_id = $request->category_id;

    if ($request->image) {
        $imagename = time().'.'.$request->image->getClientOriginalExtension();
        $request->image->move('food_img', $imagename);
        $data->image = $imagename;
    }
    $data->save();

    return redirect('view_food')->with('success', 'Food item updated!');
}




    public function orders()
    {
        $data = Order::all();
        return view('admin.order', compact('data'));
    }

    public function on_the_way($id)
    {
        $data = Order::find($id);
        $data->delivery_status = "On the way";
        $data->save();

        return redirect()->back();
    }

    public function delivered($id)
    {
        $data = Order::find($id);
        $data->delivery_status = "Delivered";
        $data->save();

        return redirect()->back();
    }

    public function canceled ($id)
    {
        $data = Order::find($id);
        $data->delivery_status = "Canceled";
        $data->save();

        return redirect()->back();
    }

    public function reservations()
    {
        $book = Book::all();
        return view('admin.reservation', compact('book'));
    }

    public function dashboard()
{
    return view('admin.index', [
        'total_user'      => \App\Models\User::count(),
        'total_food'      => Food::count(),
        'total_order'     => order::count(),
        'total_delivered' => order::where('delivery_status', 'Delivered')->count(),
    ]);
}



public function salesReport()
{
    // Aggregation + GROUP BY -> best selling food items
    $bestSelling = order::select('title')
        ->selectRaw('SUM(quantity) as total_qty')
        ->selectRaw('SUM(price) as total_revenue')
        ->groupBy('title')
        ->orderByDesc('total_qty')
        ->get();

    // Aggregation by date -> daily revenue
    $dailyRevenue = order::selectRaw('DATE(created_at) as order_date')
        ->selectRaw('SUM(price) as total')
        ->groupBy('order_date')
        ->orderByDesc('order_date')
        ->get();

    // JOIN -> orders with the customer who placed them (orders.user_id -> users.id)
    $customerOrders = DB::table('orders')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->select('users.name', 'users.email', 'orders.title', 'orders.price', 'orders.delivery_status', 'orders.created_at')
        ->orderByDesc('orders.created_at')
        ->get();

    return view('admin.sales_report', compact('bestSelling', 'dailyRevenue', 'customerOrders'));
}


public function reviews()
{
    $reviews = Review::with(['food', 'user'])->latest()->get();
    return view('admin.reviews', compact('reviews'));
}

public function categories()
{
    Category::ensureDefaultCategories();
    $topCategories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

    return view('admin.categories', compact('topCategories'));
}

public function addCategory(Request $request)
{
    Category::ensureDefaultCategories();

    $request->validate([
        'name'      => 'required|string|max:255',
        'parent_id' => 'nullable|exists:categories,id',
    ]);

    Category::create([
        'name'      => $request->name,
        'parent_id' => $request->parent_id ?: null,
    ]);

    return redirect()->back()->with('success', 'Category added successfully!');
}

public function deleteCategory($id)
{
    $category = Category::findOrFail($id);

    if ($category->children()->exists()) {
        $category->children()->delete();
    }

    $category->delete();

    return redirect()->back()->with('success', 'Category deleted successfully!');
}


}
