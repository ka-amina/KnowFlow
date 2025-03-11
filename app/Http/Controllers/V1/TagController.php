<?php

namespace App\Http\Controllers\V1;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Controllers\Controller;
use App\Interfaces\TagInterface;

class TagController extends Controller
{

    public $tagInterface;

    public function __construct(TagInterface $tagInterface)
    {
        $this->tagInterface=$tagInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->tagInterface->getAll();
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
    public function store(StoreTagRequest $request)
    {
        return $this->tagInterface->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return $this->tagInterface->getById($tag->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        return $this->tagInterface->update($tag->id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        return $this->tagInterface->delete($tag->id);
    }
}
