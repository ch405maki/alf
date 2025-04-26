<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'department'])
            ->latest()
            ->get();

        $userId = Auth::id();
        $departments = Department::all();
        
        return Inertia::render('Post/Index', [
            'posts' => $posts,
            'departments' => $departments,
            'userId' => $userId,
        ]);
    }

    public function create()
    {
        $posts = Post::with(['user', 'department'])
            ->latest()
            ->get();

        $userId = Auth::id();
        $departments = Department::all();
        
        return Inertia::render('Post/Create', [
            'posts' => $posts,
            'departments' => $departments,
            'userId' => $userId,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'document' => 'nullable|file|max:2048',
            'link' => 'nullable|url',
            'images' => 'nullable|array',
            'images.*' => 'image|max:10240', // 10MB max per image
        ]);

        try {
            $postData = $validated;
            
            // Handle document upload
            if ($request->hasFile('document')) {
                $postData['document'] = $request->file('document')->store('posts/documents', 'public');
            }

            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('posts/images', 'public');
                    $imagePaths[] = Storage::url($path);
                }
                $postData['image'] = $imagePaths;
            }

            $post = Post::create($postData);

            return response()->json([
                'message' => 'Post created successfully',
                'post' => $post
            ], 201);

        } catch (\Exception $e) {
            Log::error('Post creation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show(Post $post)
    {
        return $post->load(['user', 'department']);
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'category' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
            'document' => 'nullable|string',
            'link' => 'nullable|url',
            'image' => 'nullable|string',
            'status' => 'in:draft,published,archived',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $post->update($data);

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }
}
