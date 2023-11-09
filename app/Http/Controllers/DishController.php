<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;
use Validator;

use Illuminate\Support\Facades\Auth;

class DishController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);

        $dishes = Dish::limit($limit)->offset($offset)->get();

        return response()->json($dishes);
    }

    public function show($id)
    {
        $dish = Dish::find($id);

        if (!$dish) {
            return response()->json(['error' => 'Dish not found'], 404);
        }

        return response()->json($dish);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Dish::rules());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $dish = Dish::create($request->all());

        return response()->json($dish, 201);
    }

    public function update(Request $request, $id)
    {
        $dish = Dish::find($id);

        if (!$dish) {
            return response()->json(['error' => 'Dish not found'], 404);
        }

        $validator = Validator::make($request->all(), Dish::rules());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $dish->update($request->all());

        return response()->json($dish);
    }

    public function destroy($id)
    {
        $dish = Dish::find($id);

        if (!$dish) {
            return response()->json(['error' => 'Dish not found'], 404);
        }

        $dish->delete();

        return response()->json(null, 204);
    }

    public function rate(Request $request, $id)
    {
        $dish = Dish::find($id);

        if (!$dish) {
            return response()->json(['error' => 'Dish not found'], 404);
        }

        // Check if the user is authenticated 
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the user has already rated the dish
        if ($dish->ratings()->where('user_id', Auth::id())->exists()) {
            return response()->json(['error' => 'You have already rated this dish'], 400);
        }

        // Validate the request 
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new rating for the dish
        $rating = $dish->ratings()->create([
            'user_id' => Auth::id(),
            'rating' => $request->input('rating'),
        ]);

        return response()->json(['message' => 'Dish rated successfully', 'rating' => $rating], 201);
    }
}
