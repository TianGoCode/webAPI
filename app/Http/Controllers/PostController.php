<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function like(Request $request)
    {
        $post = Post::find('id',$request->input('id'));
        $credential = User::where('token', $request->input('token'))->first();

        //tc2-sai token
        if ($credential == null) {
            return redirect('/');
        }

        if ($post->is_banned == 1) {
            //sai tieu chuan hoac quoc gia
            if ($post->is_banned == true) {
                return response()->json([
                    "code" => 1010,
                    "message" => "Bai viet da bi xoa",
                ]);
                //xoa bai viet
            }


            //sd session flash
            if ($credential->is_banned == 1) {
                return response()->json([
                    "code" => 1010,
                    "message" => "ban da bi khoa tai khoan",
                ]);
                //xoa bai viet
            }

            //tc1-ok
            $is_liked = DB::table('like_post')->select('*')->where('post_id', '=', $post->id)->where('user_id', '=', $credential->id)->get();
//            if ($is_liked != null) {
//                return response()->json([
//                    "code" => "...",
//                    "message" => "bạn đã thích bài viết này rồi"
//                ]);
//            }
            DB::table('like_post')->insert([
                'post_id' => $post->id,
                'user_id' => $credential->id,
                "created_at" => date("Y/m/d h:i:s")
            ]);
            $likes = DB::table('like_post')->select('*')->where('post_id', '=', $post->id)->get();
            return response()->json([
                "code" => 1000,
                "message" => "đã thích bài viết",
                "data" => [
                    "like" => sizeof($likes),
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
        $likes = DB::table('like_post')->select('*')->where('post_id', '=', $post->id)->get();

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
        ]);

    }

    public function add_post(Request $request)
    {
        $credential = User::where('token', session()->get('token'))->first();
        if ($credential) {
            $post = new Post();
            $post->described = $request->input('described');
            $post->author_id = $credential->id;


            if ($post->described != null || $post->media != null) {
                if ($request->file('image')) {
                    $url = $request->file('image')->store('public/user' . $credential->id . '/post_image');
                    $post->media = str_replace('public/', '', $url);
                }
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
                    "url" => "https://127.0.0.1/" . $latest->id,
                    "data" => $latest
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

    public function edit_post(Request $request)
    {
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

    public function delete_post(Request $request)
    {
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

    public function report_post(Request $request)
    {
        $post = Post::find($request->input('id'));
        $currentUser = User::where('token', session()->get("token"))->first();
        if (!$currentUser) {
            return redirect('/');
        }

        if ($post == null) {
            return response()->json([
                "code" => 9992,
                "message" => "bài viết không tồn tại"
            ]);
        }

        if ($post->is_banned == 1) {
            return response()->json([
                "code" => 1010,
                "message" => "bai viet da bi khoa"
            ]);
        }

        DB::table("report_post")->insert([
            'reporter_id' => $currentUser->id,
            'reported_post_id' => $request->input('id'),
            'subject' => $request->input('subject'),
            'detail' => $request->input('detail')
        ]);
        return response()->json([
            "code" => 1000,
            "message" => "bai viet da duoc bao cao"
        ]);
    }

    public function search(Request $request) {
        $token = $request->input('token');
        $keyword = $request->input('keyword');
        $user_id = $request->input('user_id');
        $index = $request->input('index');
        $count = $request->input('count');

        $credential = User::where('token', $token)->first();
        $check_user = User::find($user_id);

        //sai ma token day login -tc2
        if ($credential == null) {
            return redirect("/");
        }


        $list_post = Post::all();
        $posts = [];

        if ($credential != null && $check_user != null && is_string($index) && is_string($count)) {

            //check xem key co trong described khong
            for ($i = 0; $i < count($list_post); $i++) {

                //truong author_id bi loi -tc7
                if ($ck = User::find($list_post[$i]->author_id) != null) {
                    continue;
                }
                //truong des or media bi loi -tc8
                if (!is_string($list_post[$i]->described) || !is_string($list_post[$i]->media)) {
                    continue;
                }

                if (strpos($list_post[$i]->described, $keyword) !== false) {
                    $like = DB::select('select user_id from like_post where post_id = ?', $list_post[$i]->id);
                    $cmt = DB::select('select id from comments where on_post = ?', $list_post[$i]->id);
                    //dem so like,cmt them vao $list
                    $list_post->like = count($like);
                    $list_post->comment = count($cmt);
                    array_push($posts, $list_post[$i]);
                }
            }

            //khong co kq nao tra ve - tc3
            if (empty($posts)) {
                return response()->json([
                    "code" => 1111,
                    "message" => "Khong co ket qua nao tra ve",
                    "data" => $posts,
                ]);
            }
            //tc1
            return response()->json([
                "code" => 1000,
                "message" => "OK",
                "data" => $posts,
            ]);
        }



        //dung ma phien nhung sai id - tc5
        if ($credential != null && $check_user == null) {
            return response()->json([
                "code" => "...",
                "message" => "đã có lỗi xảy ra",
            ]);
        }

        //dung tham so nhung k co tham so keyword -tc6
        if ($keyword == null) {
            return response()->json([
                "code" => "...",
                "message"=>"chưa có keyword"
            ]);
        }

        //tham so index va count bi loi - tc14
        if ($index == null || $count == null) {
            return response()->json([
                "code" => "lỗi sai giá trị dữ liệu tham số",
                "message" => "Tham số index hoặc count bị lỗi"
            ]);
        }

    }

    public function get_saved_search(Request $request) {
        $token = $request->input('token');
        $index = $request->input('index');
        $count = $request->input('count');


        $credential = User::where('token', $token)->first();
        //sai token -tc2
        if ($credential == null) {
            return redirect("/");
        }
        if($credential->is_banned == 1){
            return redirect('/');
        }


        $id_user = User::where('token', $token)->first();
        $list_keyword = DB::select('select id, keyword,created_at from keyword where author_id = ?', $id_user);

        //Thanh cong
        if ($token != null && $index != null && $count != null && !empty($list_keyword)) {
            return response()->json([
                "code" => 1000,
                "message" => "OK",
                "data" => $list_keyword,
            ]);
        };


        //k co gia tri tra ve tc3
        if (empty($list_keyword)) {
            return response()->json([
                "code" => "...",
                "message" => "Khong tim thay ket qua nao",
            ]);
        }
    }
}
