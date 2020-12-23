<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function like(Request $request) {
        $post = Post::find($request->input('id'));
        $credential = User::where('token', $request->input('token'))->first();

        //tc2-sai token
        if ($credential == null) {
            return redirect('/');
        }

        if ($post != null && $credential != null) {
            //sai tieu chuan hoac quoc gia
            if ($post->is_banned == true) {
                return response()->json([
                    "code" => 1010,
                    "message" => "Bai viet da bi xoa",
                ]);
                //xoa bai viet
            }


            //sd session flash
            if ($credential->is_banned == true) {
                return response()->json([
                    "code" => 1010,
                    "message" => "ban da bi khoa tai khoan",
                ]);
                //xoa bai viet
            }

            //tc1-ok
            $is_liked = DB::table('like_post')->select('*')->where('post_id','=',$post->id)->where('user_id','=',$credential->id)->get();
            if($is_liked != null){
                return response()->json([
                    "code" => "...",
                    "message" => "bạn đã thích bài viết này rồi"
                ]);
            }
            DB::table('like_post')->insert([
                'post_id'=>$post->id,
                'user_id'=>$credential->id,
                "created_at"=>date("Y/m/d h:i:s")
            ]);
            $likes = DB::table('like_post')->select('*')->where('post_id','=',$post->id)->get();
            return response()->json([
                "code" => 1000,
                "message" => "đã thích bài viết",
                "data" => [
                    "like" => sizeof($likes)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  ,
                ]
            ]);
        }
        //tc6-dung ma phien , sai id bai viet
        if ($post == null) {
            return response()->json([
                'code' => 9992,
                'message' => 'Bai viet khong ton tai',
                'data' => null
            ]);
        }

    }

    public function get_post(Request $request)
    {
        $post = Post::find($request->input('pid'));
        $user = User::find($post->author_id);
        $currentUser = User::where('token', session()->get("token"))->first();
        $comments = $post->hasCmts;
        $likes = DB::table('like_post')->select('*')->where('post_id','=',$post->id)->get();

        if (!$currentUser) {
            return redirect('/');
        }

        return response()->json([
            "code" => 1000,
            "message" => "lay bai viet thanh cong",
            "data" => [
                "id" => $post->id,
                "described" => $post->described,
                "modified" => $post->updated_at,
                "like" => '',
                "comments" => sizeof($comments),
                "is_liked" => sizeof($likes),
                "images" => "",
                "videos" => "",
                "author" => [
                    "id" => $user->id,
                    "username" => $user->name,
                    "avatar" => $user->avatar,
                    "is_online" => "1",
                ],

                "state" => "1",
                "is_blocked" => "0",
                "can_edit" => "1",
                "banned" => "0",
                "url" => "localhost:8000/get_post/" . $post->id,
                "messages" => "",
                "can_comment" => "1"
            ],
            "req" => $request->all(),
        ]);

    }

    public function add_post(Request $request)
    {
        $credential = User::where('token', session()->get('token'))->first();
        if ($credential) {
            $post = new Post();
            $post->described = $request->input('described');
            $post->author_id = $credential->id;
            $post->media = $request->input('image');


            if ($post->described != null || $post->media != null) {
                $post->save();
            } else if ($post->described == null && $post->media == null) {
                return response()->json([
                    "code" => "???",
                    "message" => "khong co gi de dang ca",
                    "data" => [
                        "id" => "...",
                        "url" => "..."
                    ]
                ]);
            }

            $latest = Post::where('author_id', $credential->id)->latest()->first();
            return response()->json([
                "code" => 1000,
                "message" => "dang bai thanh cong",
                "data" => [
                    "id" => $latest->id,
                    "url" => "https://127.0.0.1/" . $latest->id
                ]
            ]);
        } else {
            return response()->json([
                "code" => 9999,
                "message" => "dang bai k thanh cong dang nhap lai",
                "data" => $request->all()
            ]);
        }

    }

    public function edit_post(Request $request) {
        $post = Post::find($request->input('id'));

        $currentUser = User::where('token', session()->get("token"))->first();
        if (!$currentUser) {
            return redirect('/');
        }

        $post->described = $request->input('described');
        $post->touch();
        $post->save();
        return response()->json([
            "code" => 1000,
            "message" => "chinh sua bai thanh cong"
        ]);
    }

    public function delete_post(Request $request) {
        $post = Post::find($request->input('id'));
        $currentUser = User::where('token', session()->get("token"))->first();
        if (!$currentUser) {
            return redirect('/');
        }
        $post->delete();
        return response()->json([
            "code" => 1000,
            "message" => "xoa bai thanh cong"
        ]);
    }

    public function report_post(Request $request) {
        $post = Post::find($request->input('id'));
        $currentUser = User::where('token', session()->get("token"))->first();
        if (!$currentUser) {
            return redirect('/');
        }

        return response()->json([
            "code" => 1000,
            "message" => "bai viet da duoc bao cao"
        ]);
    }
}
