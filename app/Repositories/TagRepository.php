<?php

namespace App\Repositories;

use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Interfaces\TagInterface;
use App\Models\Tag;

class TagRepository implements TagInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function getAll()
    {
        return new TagCollection(Tag::get());
    }

    public function create(array $data)
    {
        $tag = Tag::create($data);
        return response()->json([
            'success' => true,
            'mwssage' => 'tag created successfully',
            'data' => new TagResource($tag),
        ], 201);
    }

    public function delete($id)
    {
        $tag = Tag::find($id);
        if ($tag) {
            $tag->delete();
        }
        return response()->json([
            'success' => true,
            'message' => 'tag deleted successfully',
            'data' => new TagResource($tag),
        ]);
    }

    public function update($id, $data) {
        $tag=Tag::find($id);

        if(!$tag){
            return response()->json(['error' => 'Tag not found'], 404);
        }
        $tag->update($data);

        return response()->json([
            'success' => true,
            'message' => 'tag updated successfully',
            'data' => new TagResource($tag),
        ], 200);
    }

    public function getById($id){
        $tag= Tag::find($id);

        if (!$tag){
            return response()->json(['error' => 'tag not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new TagResource($tag),
        ], 200);
    }
}
