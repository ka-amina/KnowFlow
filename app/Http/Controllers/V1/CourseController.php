<?php

namespace App\Http\Controllers\V1;

use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Controllers\Controller;
use App\Interfaces\CourseInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    use AuthorizesRequests;
    protected $courseInterface;

    public function __construct(CourseInterface $courseInterface)
    {
        $this->courseInterface = $courseInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->courseInterface->getAll();
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
    public function store(StoreCourseRequest $request)
    {
        // dd($request);
        return $this->courseInterface->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return $this->courseInterface->getById($course->id);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        return $this->courseInterface->update($course->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete',$course);
        return $this->courseInterface->delete($course->id);
    }

    // public function filter(Request $request)
    // {
    //     dd($request);
    //     $categoryName = $request->input('category');
    //     $difficulty = $request->input('level');
    //     return $this->courseInterface->filterCourses($categoryName, $difficulty);
    // }
    public function filter(Request $request){
        $category = $request->input('category');
        $difficulty = $request->input('level'); 
        return $this->courseInterface->filterCourses($category, $difficulty);

    }

    public function search(Request $request)
    {
        $input = $request->input('search');
        return $this->courseInterface->searchCourses($input);
    }

}
