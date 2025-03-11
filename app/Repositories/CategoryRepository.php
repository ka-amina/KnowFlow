<?php

namespace App\Repositories;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\interfaces\CategoryInterface;
use App\Models\Category;


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


        return new CategoryCollection(Category::get());
    }

    public function create(array $data)
    {
        $category= Category::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => new CategoryResource($category),
        ], 201);
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
        }
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
            'data' => new CategoryResource($category),
        ], 200);
    }

    public function update($id, $data)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => new CategoryResource($category),
        ], 200);
    }


    public function getById($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
        ], 200);
    }
}
