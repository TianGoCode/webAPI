<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function get_comment(Request $request)
    {
        $credential = User::where('token', session()->get('token'))->first();
        $post = Post::find($request->input('id'));
        $count = $request->input('count');
        $data = DB::table('comments')->join('users', 'comments.from_user', '=', 'users.id')->select('comments.*', 'users.name as user', 'users.link_avatar')->where('on_post', $post->id)->get();

        if ($credential == null) {
            return redirect("/");
        }

        if ($credential->is_banned == 1) {
            return redirect('/');
        }

        if ($post == null) {
            return response()->json([
                'code' => 9992,
                'message' => 'bài viết không tồn tại hoặc đã bị xóa',
                'data' => 'null'
            ]);
        }

        //dung tat ok


        if ($credential != null && $post != null && $count != null) {
            if ($post->is_banned == 1) {
                return response()->json([
                    'code' => 1010,
                    'message' => 'bài viết đã bị cấm',
                    'data' => $post
                ]);
            }
            return response()->json([
                "code" => 1000,
                "message" => "OK",
                "data" => $data
            ]);
        }


    }

    public function set_comment(Request $request)
    {
        $credential = User::where('token', session()->get('token'))->first();
        if ($credential == null) {
            return redirect("/");
        }
        $post = Post::find($request->input('id'));
        if ($post == null) {
            return response()->json([
                'code' => 9992,
                'message' => 'bài viết không tồn tại',
                'data' => 'null'
            ]);
        }
        if ($post->is_banned == 1) {
            return response()->json([
                'code' => 1010,
                'message' => 'bài viết đã bị cấm',
            ]);
        }
        if ($credential->is_banned == 1) {
            return redirect('/');
        }

        $comment = new Comment();
        $comment->on_post = $request->input('id');
        $comment->from_user = $credential->id;
        $comment->content = $request->input('comment');
        $comment->save();
        $latestComment = Comment::where('from_user', $credential->id)->latest()->first();

        return response()->json([
            'code' => 1000,
            'message' => 'thêm bình luận thành công',
            'data' => [
                'id' => $latestComment->id,
                'comment' => $latestComment->content,
                'created' => $latestComment->created_at,
                'poster' => [
                    'id' => $latestComment->by->id,
                    'name' => $latestComment->by->name,
                    'avatar' => $latestComment->by->link_avatar,
                ]
            ]
        ]);
    }

    public function set_comment2(Request $request)
    {
        $token = $request->input('token');
        $id_post = $request->input('id');
        $comment = $request->input('comment');
        $index = $request->input('index');
        $count = $request->input('count');
        $credential = User::where('token', $token)->first();
        $check_post = DB::select('select id from posts where id = ?', $id_post);
        $list_comment = [];


        $arr_cmt = DB::select('select id, from_user,content,created_at,is_blocked from comments where on_post = ?', $id_post);

        for ($i = $index; $i < ($index + $count); $i++) {
            $poser = [];
            $id_user = $arr_cmt[$i]->from_user;
            //check xem co chan nhau khong -tc9
            $check_black_user = DB::select('select block_id from black_list where user_id = ? and block_id = ?', [$credential->id, $id_user]);
            $check_black_cmt = DB::select('select block_id from black_list where user_id = ? and block_id = ?', [$id_user, $credential->id]);
            if ($check_black_user != null || $check_black_cmt != null) {
                continue;
            }


            $user = DB::select('select id,name,link_avatar from users where id = ?', $id_user)[0];

            array_push($poser, $user);
            $arr_cmt[$i]->poser = $poser;
            array_push($list_comment, $arr_cmt[$i]);
        }

        //sai phien -tc2
        if ($credential == null && $token == null) {
            return redirect('/');
        }

        //ok-tc1
        if ($credential != null && is_string($index) && is_string($count)) {

            if ($check_post != null) {
                //loi db -tc5
                if (empty($list_comment)) {
                    return response()->json([
                        "code" => 1001,
                        "message" => "Không thể kết nối Internet",
                    ]);
                }

                return response()->json([
                    "code" => 1000,
                    "message" => "OK",
                    "data" => $list_comment,
                ]);
            } else {
                return response()->json([
                    "code" => 9992,
                    "message" => "Bài viết không tồn tại",
                ]);
            }
        }

    }
}
