<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostRecourse;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;



class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get list of posts",
     *     description="Retrieve posts with optional title search and include user information.",
     *     tags={"Posts"},
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search keyword for filtering posts by title",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Posts retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="posts",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Sample Title"),
     *                         @OA\Property(property="content", type="string", example="Post content here"),
     *                         @OA\Property(property="released_at", type="string", example="2024-01-01"),
     *                         @OA\Property(property="image_path", type="string", nullable=true, example="/storage/posts/example.jpg"),
     *                         @OA\Property(
     *                             property="user",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=5),
     *                             @OA\Property(property="name", type="string", example="Ali"),
     *                             @OA\Property(property="email", type="string", example="test@example.com")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An error occurred while fetching posts")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Get a single post",
     *     description="Retrieve a specific post by its ID, including user information.",
     *     tags={"Posts"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Post retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="post",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Sample Title"),
     *                     @OA\Property(property="content", type="string", example="Post content here"),
     *                     @OA\Property(property="released_at", type="string", example="2024-01-01"),
     *                     @OA\Property(property="image_path", type="string", nullable=true, example="/storage/posts/example.jpg"),
     *                     @OA\Property(
     *                         property="user",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=5),
     *                         @OA\Property(property="name", type="string", example="Ali"),
     *                         @OA\Property(property="email", type="string", example="test@example.com")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Post not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An error occurred while fetching the post")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
     *     description="Create a new post with title, content, release date, and optional image.",
     *     tags={"Posts"},
     *     security={{"jwt":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Post data",
     *         @OA\JsonContent(
     *             required={"title", "content", "released_at"},
     *             @OA\Property(property="title", type="string", example="Sample Post Title"),
     *             @OA\Property(property="content", type="string", example="This is the content of the post."),
     *             @OA\Property(property="released_at", type="string", example="2024-01-01"),
     *         ),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "content", "released_at"},
     *                 @OA\Property(property="title", type="string", example="Sample Post Title"),
     *                 @OA\Property(property="content", type="string", example="This is the content of the post."),
     *                 @OA\Property(property="released_at", type="string", example="2024-01-01"),
     *                 @OA\Property(
     *                     property="image",
     *                     description="Image file to upload",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="پست با موفقیت ایجاد شد"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="post",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Sample Post Title"),
     *                     @OA\Property(property="content", type="string", example="This is the content of the post."),
     *                     @OA\Property(property="released_at", type="string", example="2024-01-01"),
     *                     @OA\Property(property="image_path", type="string", nullable=true, example="/storage/posts/example.jpg"),
     *                     @OA\Property(
     *                         property="user",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=5),
     *                         @OA\Property(property="name", type="string", example="Ali"),
     *                         @OA\Property(property="email", type="string", example="test@example.com")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation failed")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An error occurred while creating the post")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Update a post",
     *     description="Update the specified post with new data. You can also upload a new image.",
     *     tags={"Posts"},
     *     security={{"jwt":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post to update",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated post data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", example="Updated Post Title"),
     *                 @OA\Property(property="content", type="string", example="Updated content here."),
     *                 @OA\Property(property="released_at", type="string", example="2024-01-02"),
     *                 @OA\Property(
     *                     property="image",
     *                     description="New image file to upload (optional)",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="پست با موفقیت به‌روزرسانی شد"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="post",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Updated Post Title"),
     *                     @OA\Property(property="content", type="string", example="Updated content here."),
     *                     @OA\Property(property="released_at", type="string", example="2024-01-02"),
     *                     @OA\Property(property="image_path", type="string", nullable=true, example="/storage/posts/updated.jpg"),
     *                     @OA\Property(
     *                         property="user",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=5),
     *                         @OA\Property(property="name", type="string", example="Ali"),
     *                         @OA\Property(property="email", type="string", example="test@example.com")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation failed")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An error occurred while updating the post")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete a post",
     *     description="Delete the specified post by its ID.",
     *     tags={"Posts"},
     *     security={{"jwt":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="پست با موفقیت حذف شد")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Post not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An error occurred while deleting the post")
     *         )
     *     )
     * )
     */
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
