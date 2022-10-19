<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function getComment()
    {
        $comment = Comment::all();

        if (count($comment)) {
            return response()->json([$comment]);
        } else {
            return ['result' => 'No Comment yet'];
        }
    }

}
