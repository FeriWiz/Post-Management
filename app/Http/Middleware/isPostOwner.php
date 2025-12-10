<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isPostOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $post = $request->route('post');
        $user = auth()->user();

        if ($post && $user && $post->user_id === $user->id) {
            return $next($request);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'شما اجازه دسترسی به این پست را ندارید'
        ], 403);
    }
}
