<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodApiController extends Controller
{
    // GET /api/foods
    public function index()
    {
        $foods = Food::all();
        return response()->json([
            'status' => true,
            'count'  => $foods->count(),
            'data'   => $foods,
        ]);
    }

    // GET /api/foods/{id}
    public function show($id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json(['status' => false, 'message' => 'Food not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $food]);
    }

    // POST /api/foods
    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'detail' => 'required|string',
            'price'  => 'required|numeric|min:0',
        ]);

        $food = Food::create($request->only('title', 'detail', 'price', 'image'));

        return response()->json(['status' => true, 'message' => 'Food created', 'data' => $food], 201);
    }

    // PUT /api/foods/{id}
    public function update(Request $request, $id)
    {
        $food = Food::find($id);
        if (!$food) {
            return response()->json(['status' => false, 'message' => 'Food not found'], 404);
        }

        $food->update($request->only('title', 'detail', 'price', 'image'));
        return response()->json(['status' => true, 'message' => 'Food updated', 'data' => $food]);
    }

    // DELETE /api/foods/{id}
    public function destroy($id)
    {
        $food = Food::find($id);
        if (!$food) {
            return response()->json(['status' => false, 'message' => 'Food not found'], 404);
        }

        $food->delete();
        return response()->json(['status' => true, 'message' => 'Food deleted']);
    }
}