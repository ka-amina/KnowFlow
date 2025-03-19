<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Interfaces\VideoInterface;
use App\Models\Course;

class VideoController extends Controller
{
    protected $videoRepository;

    public function __construct(VideoInterface $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        // dd($id);
        return $this->videoRepository->getByCourseId($id);
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
    public function store(StoreVideoRequest $request, $id)
    {
        return $this->videoRepository->create($request, $id);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->videoRepository->getById($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoRequest $request, $id)
    {
        $videoResponse = $this->videoRepository->getById($id);
        
        if ($videoResponse->getStatusCode() === 404) {
            return $videoResponse;
        }
        
        $video = json_decode($videoResponse->getContent())->data;
        
        // Get course for the video
        $course = Course::findOrFail($video->course_id);
        
        return $this->videoRepository->update($id, $request->validated());
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $videoResponse = $this->videoRepository->getById($id);
        
        // Check if video not found
        if ($videoResponse->getStatusCode() === 404) {
            return $videoResponse;
        }
        
        $video = json_decode($videoResponse->getContent())->data;
        
         Course::findOrFail($video->course_id);
        
        
        return $this->videoRepository->delete($id);
    }
}
