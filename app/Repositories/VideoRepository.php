<?php

namespace App\Repositories;

use App\Http\Resources\VideoCollection;
use App\Http\Resources\VideoResource;
use App\Interfaces\VideoInterface;
use App\Models\Video;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class VideoRepository implements VideoInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        try {
            return new VideoCollection(Video::all());
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve videos'], 500);
        }
    }

    public function getByCourseId($courseId)
    {
        // dd($courseId);
        try {
            $videos = Video::where('course_id', $courseId)->get();
            return response()->json([
                'success' => true,
                'data' => new VideoCollection($videos),
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve videos'], 500);
        }
    }

    public function getById($id)
    {
        try {
            $video = Video::with('course')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => new VideoResource($video),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Video not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve video'], 500);
        }
    }

    public function create($data, $courseId)
    {
        try {
            // dd($courseId);
            $video = Video::create([
                "title" => $data->title,
                "description" => $data->description,
                "url" => $data->url,
                "course_id" => $courseId
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Video created successfully',
                'data' => new VideoResource($video),
            ], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to create video'], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function update($id, $data)
    {
        try {
            $video = Video::findOrFail($id);
            $video->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Video updated successfully',
                'data' => new VideoResource($video),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Video not found'], 404);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to update video'], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }


    public function delete($id)
    {
        try {
            $video = Video::findOrFail($id);
            $video->delete();
            return response()->json([
                'success' => true,
                'message' => 'Video deleted successfully',
                'data' => new VideoResource($video),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Video not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete video'], 500);
        }
    }
}
