<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Recipe;

class CommentController extends Controller
{
    public function index($recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);
        $comments = $recipe->comments;
        return response()->json($comments);
    }

    public function create(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'recipe_id' => 'required|exists:recipes,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $comment = Comment::create($request->all());
        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment], 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $temp_user = auth()->user();

        if ($comment->user_id != $temp_user->id) {
            return response()->json(['error' => "Unauthorized"], 400);
        }

        $request->validate([
            'content' => 'sometimes|required|string',
        ]);

        $comment->update($request->all());
        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment]);
    }

    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        $temp_user = auth()->user();

        if ($comment->user_id != $temp_user->id) {
            return response()->json(['error' => "Unauthorized"], 400);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
