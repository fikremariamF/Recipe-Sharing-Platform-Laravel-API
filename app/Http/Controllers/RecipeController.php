<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Ingredient;

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
            'ingredients' => 'required',
            'instruction' => 'required|string',
            'preparation_time' => 'required|integer',
            'user_id' => 'required|exists:users,id',
        ]);
        $recipe = Recipe::create($request->all());
        foreach ($request['ingredients'] as $ingredient) {
            Ingredient::create(['name'=>$ingredient, 'recipe_id'=> $recipe->id]);
        }
        return response()->json(['message' => 'Recipe created successfully', 'recipe' => $recipe], 201);
    }

    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);
        $temp_user = auth()->user();

        if($recipe->user_id != $temp_user->id){
            return response()->json(['error' => "Unauthorized"], 400);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'instruction' => 'sometimes|required|string',
            'preparation_time' => 'sometimes|required|integer',
        ]);

        $recipe->update($request->all());
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

