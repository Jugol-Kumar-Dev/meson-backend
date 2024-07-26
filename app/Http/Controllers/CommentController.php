<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(){
        return "index";
    }

    public function submit_comment(Request $request){
        if (!Auth::check()){
            return redirect()->route('login');
        }
        Comment::create([
            'blog_id' => $request->blog_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);
        return back()->with(['msg' => 'Thanks For Comment']);
    }

    public function replayComment(Request $request){

//        return $request;

        if (!Auth::check()){
            return redirect()->route('login');
        }
        Comment::create([
            'comment_id' => $request->commentId,
            'blog_id'    => $request->blogId,
            'user_id'    => Auth::id(),
            'message'    => $request->message,
            'is_replay'  => 1,
            'is_approve' => 1
        ]);
        return back()->with(['msg' => 'Thanks For Comment']);
    }

    public function deleteBlogComment($id){
        Comment::findOrFail($id)->delete();
        return back()->with(['msg' => 'Comment Deleted Successfull...']);
    }


}
