<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Carbon;
use App\ApiHelper;

class PostController extends Controller
{
    use ApiHelper;
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    function fetch()
    {
       $posts = Post::fetch();
       if(!$posts) return $this->error('No posts found');
       return $this->success($posts, 'Posts fetched successfully');
    }

    function submit(Request $request)
    {
        $id = $request->post_id;
        $request->validate([
            'title'   => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        $param = [
            'post_title'   => $request->title,
            'post_content' => $request->content,
            'user_id'      => $request->user()->id,
        ];

        if(!$id)
        {
            $param['post_created'] = Carbon::now();
        } else {
            $param['post_modify'] = Carbon::now();
        }

        $result = Post::submit($id, $param);
        return response()->json([
            'message' => boolval($result) ? 'Post updated successfully' : 'Post created successfully',
            'data'    =>  $result ? Post::fetch($result) : []
        ]);
    }
    function delete($id)
    {
        $post = Post::fetch($id);
        if(!$post) return response()->json([
            'message' => 'Post not found',
            'data'    => []
        ]);
        $result =   $post->delete();
        return response()->json([
            'message' => boolval($result) ? 'Post deleted successfully' : 'Post not found',
        ]);
    }
}