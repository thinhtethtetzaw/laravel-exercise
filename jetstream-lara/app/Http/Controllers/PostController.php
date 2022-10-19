<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function getPost()
    {
        $post = Post::all();

        if (count($post)) {
            return response()->json([$post]);
        } else {
            return ['result' => 'No Post yet'];
        }
    }


    public function searchPosts($user_id)
    {
        $post = Post::where("user_id", $user_id)->get();

        if (count($post)) {
            return response()->json([$post]);
        } else {
            return ['result' => 'No Post Yet'];
        }
    }

    public function newPost(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "content" => "required",
            "user_id" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        } else {
            $inputs = $request->all();
            $post = Post::create($inputs);
            return response()->json(['status' => 'success', "post" => $post]);
        }


    }

}





