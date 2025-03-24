<?php

namespace App\Repositories;

use App\Http\Resources\EnrollCollection;
use App\Http\Resources\EnrollResource;
use App\Interfaces\EnrollInterface;
use App\Models\Enrollement;
use Carbon\Carbon;

class EnrollRepository implements EnrollInterface
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
        // dd("works");
        $enrollments = Enrollement::with(['course', 'student'])->get();
        return new EnrollCollection($enrollments);
        // dd($enrollments);
    }

    public function findById($id)
    {
        $enrollment= Enrollement::with(['course', 'student', 'updatedBy'])->find($id);
        if (!$enrollment) {
            return response()->json([
                'message' => 'Enrollment not found.'
            ], 404);
        }

        return response()->json([
            'data' => new EnrollResource($enrollment)
        ]);
    }

    public function create($data)
    {
        return Enrollement::create($data);
    }

    public function update($id, $data)
    {
        $enrollment = Enrollement::findById($id);

        if (!$enrollment) {
            return null;
        }

        $enrollment->update($data);

        return $enrollment;
    }

    public function delete($id)
    {
        $enrollment = Enrollement::find($id);

        if (!$enrollment) {
            return false;
        }

        $result = $enrollment->delete();
        if (!$result) {
            return response()->json([
                'message' => 'Enrollment not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Enrollment deleted successfully.'
        ]);
    }

    public function updateStatus($id, $status)
    {
        $enrollment = Enrollement::findById($id);

        if (!$enrollment) {
            return null;
        }

        $enrollment->status = $status;
        $enrollment->save();

        return $enrollment;
    }

    public function isUserEnrolled($userId, $courseId)
    {
        return Enrollement::where([
            'user_id' => $userId,
            'course_id' => $courseId
        ])->exists();
    }
}
