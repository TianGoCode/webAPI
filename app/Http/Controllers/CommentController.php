<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function get_comment(Request $request) {
        $credential = User::where('token', session()->get('token'))->first();
        $post = Post::find($request->input('id'));
        $count = $request->input('count');
        $data = DB::table('comments')->join('users', 'comments.from_user', '=', 'users.id')->select('comments.*', 'users.name as user', 'users.link_avatar')->where('on_post', $post->id)->get();

        //dung tat ok
        if ($credential != null && $post != null && $count != null) {
            return response()->json([
                "code" => 1000,
                "message" => "OK",
                "data" => $data
            ]);
        }

        if ($credential == null) {
            return redirect("/");
        }
    }
}
