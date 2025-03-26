<?php

namespace App\Repositories;

use App\Interfaces\CourseInterface;
use App\Models\Course;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Exception;
use App\Models\Category;

class CourseRepository implements CourseInterface
{
    public function getAll()
    {
        try {
            return new CourseCollection(Course::all());
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve courses'], 500);
        }
    }

    public function getById($id)
    {
        try {
            $course = Course::findOrFail($id);
            return response()->json(new CourseResource($course), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Course not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function create($data)
    {
        try {
            $course = Course::create($data);

            if (!empty($data['tags'])) {
                $course->tags()->attach($data['tags']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Course created successfully',
                'data' => new CourseResource($course->load('tags')),
            ], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to create course'], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function update($id, $data)
    {
        try {
            $course = Course::find($id);
            $course->update($data);

            if (isset($data['tags'])) {
                $course->tags()->sync($data['tags']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully',
                'data' => new CourseResource($course),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Course not found'], 404);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to update course'], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function delete($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();

            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Course not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete course'], 500);
        }
    }

    public function filterCourses($categoryName = null, $level = null)
    {
        try {
            $query = Course::query();

            if ($categoryName) {
                $query->whereHas('category', function ($category) use ($categoryName) {
                    $category->where('name', 'LIKE', "%{$categoryName}%");
                });
            }

            if ($level) {
                $query->where('level', $level);
            }

            $courses = $query->get();

            return response()->json([
                'success' => true,
                'data' => CourseResource::collection($courses)
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to filter courses: ' . $e->getMessage()], 500);
        }
    }

    public function searchCourses($query)
    {
        try {
            $courses = Course::where('title', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->get();

            return response()->json([
                'success' => true,
                'data' => CourseResource::collection($courses)
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to search courses'], 500);
        }
    }
}
