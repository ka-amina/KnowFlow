<?php

namespace App\Repositories;

use App\Interfaces\CourseInterface;
use App\Models\Course;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseCollection;

class CourseRepository implements CourseInterface
{
    public function getAll()
    {
        return new CourseCollection(Course::all());
    }

    public function getById($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }
        return response()->json(new CourseResource($course));
    }

    public function create(array $data)
    {
        $course = Course::create($data);

        if (!empty($data['tags'])) {
            $course->tags()->attach($data['tags']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully',
            'data' => new CourseResource($course->load('tags')),
        ], 201);
    }

    public function update($id, array $data)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }
        $course->update($data);

        if (isset($data['tags'])) {
            $course->tags()->sync($data['tags']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully',
            'data' => new CourseResource($course),
        ]);
    }

    public function delete($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }
        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }
}
