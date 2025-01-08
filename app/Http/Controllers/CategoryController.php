<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\Category\StoreCategoryRequest;
use App\Http\Requests\Api\Category\UpdateCategoryRequest;
use App\Http\Resources\ResponseResource;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $categories = auth()->user()->categories()->paginate(5);
            return new ResponseResource([
                'message' => '',
                'data' => $categories
            ]);

        } catch (Throwable $th) {
            return new ResponseResource([
                'error' => true,
                'message' => 'An error occurred',
                'errors' => [],  // Replace with actual error details
            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {

           $user = auth()->user();
            if (!$user) {
                return new ResponseResource([
                    'error' => true,
                    'message' => 'Invalid Credentials',
                    'errors' => [],  // Replace with actual error details
                ]);
            }

            $category = $user->categories()->create([
                'name'=>$request->name,
                'slug'=>Str::slug($request->name),
                'active'=>($request->active==false) ? false : true
            ]);

            return new ResponseResource([
                'message' => 'Category created successfully',
                'data' => $category
            ]);

        } catch (Throwable $th) {
            return new ResponseResource([
                'error' => true,
                'message' => 'An error occurred',
                'errors' => [],  // Replace with actual error details
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            if (!$category) {
                return new ResponseResource([
                    'error' => true,
                    'message' => 'Category not found.',
                    'errors' => [],  // Replace with actual error details
                ]);
            }

            return new ResponseResource([
                'message' => '',
                'data' => $category
            ]);

        } catch (Throwable $th) {
            return new ResponseResource([
                'error' => true,
                'message' => 'An error occurred',
                'errors' => [],  // Replace with actual error details
            ]);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return new ResponseResource([
                    'error' => true,
                    'message' => 'Invalid Credentials',
                    'errors' => [],  // Replace with actual error details
                ]);
            }

            $user->categories()->where('id', $category->id)->update([
                'name'=>$request->name,
                'slug'=>Str::slug($request->name),
                'active'=>($request->active==false) ? false : true
            ]);
            $category  = $category->refresh();

            return new ResponseResource([
                'message' => 'Category updated successfully',
                'data' => $category
            ]);

        } catch (Throwable $th) {
            return new ResponseResource([
                'error' => true,
                'message' => 'An error occurred',
                'errors' => [],  // Replace with actual error details
            ]);
        }
    }

}
