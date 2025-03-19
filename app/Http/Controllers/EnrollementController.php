<?php

namespace App\Http\Controllers;

use App\Models\Enrollement;
use App\Http\Requests\StoreEnrollementRequest;
use App\Http\Requests\UpdateEnrollementRequest;
use App\Http\Resources\EnrollCollection;
use App\Http\Resources\EnrollResource;
use App\Interfaces\CourseInterface;
use App\Interfaces\EnrollInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollementController extends Controller
{

    protected $enrollmentRepository;
    protected $courseRepository;

    public function __construct(
        EnrollInterface $enrollmentRepository,
        CourseInterface $courseRepository
    ) {
        $this->enrollmentRepository = $enrollmentRepository;
        $this->courseRepository = $courseRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->enrollmentRepository->getAll();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnrollementRequest $request)
    {
        $userId = Auth::id();
        $courseId = $request->input('course_id');

        $course = $this->courseRepository->getById($courseId);
        
        if (!$course) {
            return response()->json([
                'message' => 'Course not found.'
            ], 404);
        }

        if ($this->enrollmentRepository->isUserEnrolled($userId, $courseId)) {
            return response()->json([
                'message' => 'You are already enrolled in this course.'
            ], 422);
        }

        $enrollment = $this->enrollmentRepository->create([
            'course_id' => $courseId,
            'user_id' => $userId,
            'status' => 'pending',
            'enrolled_at' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Enrollment request submitted successfully.',
            'data' => new EnrollResource($enrollment)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $enrollment = $this->enrollmentRepository->findById($id);

        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollement $enrollement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnrollementRequest $request, Enrollement $enrollement)
    {
        $status = $request->input('status');

        $enrollment = $this->enrollmentRepository->updateStatus($enrollement->id, $status);

        if (!$enrollment) {
            return response()->json([
                'message' => 'Enrollment not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Enrollment status updated successfully.',
            'data' => new EnrollResource($enrollment)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return $this->enrollmentRepository->delete($id);

        
    }


    public function enrollInCourse($courseId)
    {
        $userId = Auth::id();

        $course = $this->courseRepository->getById($courseId);
        
        if (!$course) {
            return response()->json([
                'message' => 'Course not found.'
            ], 404);
        }

        if ($this->enrollmentRepository->isUserEnrolled($userId, $courseId)) {
            return response()->json([
                'message' => 'You are already enrolled in this course.'
            ], 422);
        }

        $enrollment = $this->enrollmentRepository->create([
            'course_id' => $courseId,
            'user_id' => $userId,
            'status' => 'pending',
            'enrolled_at' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Enrollment request submitted successfully.',
            'data' => new EnrollResource($enrollment)
        ], 201);
    }
}
