<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\User;

class RecipeController extends Controller
{
    public function getAllRecipes()
    {
        $recipes = Recipe::all();
        return response()->json($recipes);
    }

    public function getRecipesByUser($id)
    {
        $user = User::findOrFail($id);
        $recipes = $user->recipes;
        return response()->json($recipes);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'instruction' => 'required|string',
            'preparation_time' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string|max:255',
        ]);

        $recipe = Recipe::create($request->only(['title', 'instruction', 'preparation_time', 'user_id']));

        foreach ($request->ingredients as $ingredientData) {
            $recipe->ingredients()->create($ingredientData);
        }

        return response()->json(['message' => 'Recipe created successfully', 'recipe' => $recipe], 201);
    }

    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);
        $temp_user = auth()->user();

        if ($recipe->user_id != $temp_user->id) {
            return response()->json(['error' => "Unauthorized"], 400);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'instruction' => 'sometimes|required|string',
            'preparation_time' => 'sometimes|required|integer',
            'ingredients' => 'sometimes|array',
            'ingredients.*.name' => 'sometimes|required|string|max:255',
        ]);

        $recipe->update($request->only(['title', 'instruction', 'preparation_time']));

        if ($request->has('ingredients')) {
            $recipe->ingredients()->delete(); // Remove existing ingredients
            foreach ($request->ingredients as $ingredientData) {
                $recipe->ingredients()->create($ingredientData);
            }
        }

        return response()->json(['message' => 'Recipe updated successfully', 'recipe' => $recipe]);
    }

    public function delete($id)
    {
        $recipe = Recipe::findOrFail($id);
        $temp_user = auth()->user();

        if ($recipe->user_id != $temp_user->id) {
            return response()->json(['error' => "Unauthorized"], 400);
        }
        $recipe->delete();
        return response()->json(['message' => 'Recipe deleted successfully']);
    }
}