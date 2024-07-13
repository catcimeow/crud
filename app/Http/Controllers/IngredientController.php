<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Validator;

class IngredientController extends Controller
{
    public function index(){
        $ingredients = Ingredient::all();
        return response()->json($ingredients);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $ingredient = new Ingredient;
        $ingredient->name = $request->name;
        $ingredient->unit = $request->unit;
        
        // Handle file upload
        $file = $request->file('image');
        $filePath = $file->store('public/images');
        $ingredient->image_path = Storage::url($filePath);

        $ingredient->save();

        return response()->json(["success" => "Ingredient created successfully.", "ingredient" => $ingredient, "status" => 200]);
    }

    public function show($id){
        $ingredient = Ingredient::find($id);
        return response()->json($ingredient);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $ingredient = Ingredient::find($id);
        if (!$ingredient) {
            return response()->json(['error' => 'Ingredient not found.', 'status' => 404]);
        }

        $ingredient->name = $request->name;
        $ingredient->unit = $request->unit;

        // Handle file upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filePath = $file->store('public/images');
            $ingredient->image_path = Storage::url($filePath);
        }

        $ingredient->save();

        return response()->json(["success" => "Ingredient updated successfully.", "ingredient" => $ingredient, "status" => 200]);
    }

    public function destroy($id) {
        $ingredient = Ingredient::find($id);
        if ($ingredient) {
            Ingredient::destroy($id);
            return response()->json(['success' => 'Ingredient deleted successfully.', 'status' => 200]);
        }
        return response()->json(['error' => 'Ingredient not found.', 'status' => 404]);
    }
}
