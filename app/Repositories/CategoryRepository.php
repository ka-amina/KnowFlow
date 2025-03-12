<?php

namespace App\Repositories;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\interfaces\CategoryInterface;
use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class CategoryRepository implements CategoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function getAll()
    {
        // return Category::all();
        // return response()->json([
        //     'message' => 'Successfully created user!',
        //     'accessToken' => $token,
        // ], 201);
        // dd(Category::all());
        try {
            return new CategoryCollection(Category::all());
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve categories'], 500);
        }
    }

    public function create(array $data)
    {
        try {
            $category = Category::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => new CategoryResource($category),
            ], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to create category'], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
                'data' => new CategoryResource($category),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete category'], 500);
        }
    }

    public function update($id, $data)
    {
        try {
            $category = Category::find($id);
            $category->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => new CategoryResource($category),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to update category'], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function getById($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => new CategoryResource($category),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve category'], 500);
        }
    }
}
