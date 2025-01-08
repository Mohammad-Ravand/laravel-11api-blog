<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\Post\GetAllPostsRequest;
use App\Http\Requests\Api\Post\StorePostRequest;
use App\Http\Requests\Api\Post\UpdatePostRequest;
use App\Http\Resources\ResponseResource;
use App\Models\Post;
use Illuminate\Support\Str;
use Throwable;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllPostsRequest $request)
    {
        try {
            $search = $request->search ?? '';
            $posts = auth()->user()->posts()
                ->when(strlen($search)>0, fn ($query) => $query->where('content', 'like', "%{$search}%"))
                ->paginate(5);
            return new ResponseResource([
                'message' => '',
                'data' => $posts
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
    public function store(StorePostRequest $request)
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

            $post = $user->posts()->create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->text_content,
                'active' => $request->active ? true : false, // Simplified ternary
            ]);

            // Sync categories after creating the post
            $post->categories()->sync($request->categories);

            return new ResponseResource([
                'message' => 'Category created successfully',
                'data' => $post->load('categories')
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
    public function show(Post $post)
    {
        try {
            if (!$post) {
                return new ResponseResource([
                    'error' => true,
                    'message' => 'Post not found.',
                    'errors' => [],  // Replace with actual error details
                ]);
            }

            return new ResponseResource([
                'message' => '',
                'data' => $post
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
    public function update(UpdatePostRequest $request, Post $post)
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

            $post  = $post->refresh();

            $user->posts()->where('id', $post->id)->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->text_content,
                'active' => $request->active ? true : false,
            ]);

            // Sync categories after creating the post
            $post->categories()->sync($request->categories);


            return new ResponseResource([
                'message' => 'Post updated successfully',
                'data' => $post->load('categories')
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
