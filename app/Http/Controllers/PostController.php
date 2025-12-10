<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostRecourse;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;



class PostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');
            $posts = Post::with('user')
                ->where('title', 'LIKE', '%' . $search . '%')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'posts' => PostRecourse::collection($posts)
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'مشکلی در دریافت پست‌ها پیش آمد'
            ], 500);
        }
    }

    public function show(Post $post)
    {
        try {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'post' => new PostRecourse($post)
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'مشکلی در دریافت پست‌ها پیش آمد'
            ], 500);
        }
    }

    public function store(CreatePostRequest $request)
    {
        try {
            $post = Post::createPost($request->validated());

            return response()->json([
                'status' => 'پست با موفقیت ایجاد شد',
                'data' => [
                    'post' => new PostRecourse($post)
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'مشکلی در ایجاد پست پیش آمد'
            ], 500);
        }
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            $post = Post::updatePost($post, $request->validated());
            return response()->json([
                'status' => 'پست با موفقیت به‌روزرسانی شد',
                'data' => [
                    'post' => new PostRecourse($post)
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'مشکلی در به‌روزرسانی پست پیش آمد'
            ], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return response()->json([
                'status' => 'پست با موفقیت حذف شد'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'مشکلی در حذف پست پیش آمد'
            ], 500);
        }
    }
}
