<?php

namespace App\Http\Controllers\V1;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\interfaces\CategoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public $categoryInterface;

    public function __construct(CategoryInterface $categoryInterface)
    {
        $this->categoryInterface = $categoryInterface;
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->categoryInterface->getAll();
        // $this->categoryInterface->getAll();


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('isAdmin');
        return $this->categoryInterface->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // return $this->categoryInterface->getById($category);
        return $this->categoryInterface->getById($category->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateCategoryRequest $request, Category $category)
    // {
    //     //
    // }
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('isAdmin');
        return $this->categoryInterface->update($category->id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        return $this->categoryInterface->delete($category->id);
    }
}
