<?php

namespace App\Repositories;

use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Interfaces\TagInterface;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Exception;

class TagRepository implements TagInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function getAll()
    {
        try {
            return new TagCollection(Tag::all());
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve tags'], 500);
        }
    }

    public function create(array $data)
    {
        try {
            $tag = Tag::create($data);
            return response()->json([
                'success' => true,
                'mwssage' => 'tag created successfully',
                'data' => new TagResource($tag),
            ], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to create tag'], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function delete($id)
    {
        try {
            $tag = Tag::find($id);
            $tag->delete();

            return response()->json([
                'success' => true,
                'message' => 'tag deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Tag not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete tag'], 500);
        }
    }

    public function update($id, $data)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->update($data);

            return response()->json([
                'success' => true,
                'message' => 'tag updated successfully',
                'data' => new TagResource($tag),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'tag not found'], 404);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to update tag'], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function getById($id)
    {
        try {
            $tag = Tag::find($id);
            return response()->json([
                'success' => true,
                'data' => new TagResource($tag),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Tag not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve tag'], 500);
        }
    }
}
