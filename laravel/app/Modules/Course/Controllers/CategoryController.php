<?php

namespace App\Modules\Course\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    const CATEGORY_NOT_FOUND = 'Category not found';
    
    public function index(){
        try {
            $categories = Category::all();
            return response()->json([
                'is_success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'is_success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id){
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                'is_success' => true,
                'data' => $category
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'is_success' => false,
                'error' => self::CATEGORY_NOT_FOUND
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'is_success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        try {
            $category = Category::create($data);
            return response()->json([
                'is_success' => true,
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'is_success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id){
        try {
            $category = Category::findOrFail($id);
            $data = $request->only(['name', 'description']);

            if (empty($data)) {
                return response()->json([
                    'is_success' => false,
                    'error' => 'No data provided for update'
                ], 400);
            }

            $category->update($data);
            $category->refresh();
                        
            return response()->json([
                'is_success' => true,
                'data' => $category
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'is_success' => false,
                'error' => self::CATEGORY_NOT_FOUND
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'is_success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id){
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json([
                'is_success' => true,
                'message' => 'Category deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'is_success' => false,
                'error' => self::CATEGORY_NOT_FOUND
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'is_success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
