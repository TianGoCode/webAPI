<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

function generateRandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

Route::get('/wel', function () {
    return view('welcome');
});

Route::get('/signup', function () {
    return view('signUp');
});
Route::get('/login', function () {
    return view('logIn');
});
Route::view('/test', 'layout.layout');
//Route::view('/test2', 'logged.change_info');
Route::get('/test2', function () {
    return view('logged.change_info', ['posts' => $posts = Post::where('author_id', session()->get('data')->id)->get()]);
});

Route::get('/', function () {
    return view('home');
});

Route::post('/signup', function (Request $request) {
    // lay ra thong tin request duoc gui len
    $user = new User();
    $user->phonenumber = $request->input('phone');
    $user->password = $request->input('pass');
//    $user->uuid = $request->input('uuid');

    $duplicate = User::where('phonenumber', $user->phonenumber)->first();

    if ($duplicate != null) {
        //kiem tra trung lap sdt
        return response()->json([
            "code" => 9996,
            "message" => "Người dùng đã tồn tại",
            "data" => $request->all()
        ]);
    }
    if ($request->input('phone') == null) {
        //neu chua nhap sdt
        return response()->json([
            "code" => "error",
            "message" => "ban chua nhap so dien thoai",
            "data" => $request->all()
        ]);
    }
    if ($request->input('pass') == null) {
        // neu chua nhap mk
        return response()->json([
            "code" => "error",
            "message" => "ban chua nhap mat khau",
            "data" => $request->all()
        ]);
    }
    if ($request->input('phone') == $request->input('pass')) {
        //neu mk trung sdt
        return response()->json([
            "code" => "error",
            "message" => "ban da nhap sodien thoai trung mat khau",
            "data" => $request->all(),
            "user" => $user
        ]);
    }

    $user->save();
    return response()->json([
        "code" => 1000,
        "message" => "dang ky thanh cong",
        "data" => $request->all()
    ]);
});

Route::post('/login', function (Request $request) {
    $token = generateRandomString(10);
    $credentials = User::where('phonenumber', $request->input('phonenumber'))->first();
    $phonenumber = $request->input('phonenumber');
    $password = $request->input('password');
    //1, kiem tra phonenumber
    if ($phonenumber == null) {
        return response()->json([
            "code" => 9994,
            "message" => "chua nhap sdt",
            "data" => [
                "id" => "no infomation",
                "username" => "no infomation",
                "token" => $request->input('token'),
                "avatar" => "no infomation"
            ],

        ]);
    }
    //sai ding dang sdt: chua lam dc
    //dung sdt
    if ($credentials != null) {
        //mat khau de trong
        if ($request->input('password') == null) {
            return response()->json([
                "code" => 9994,
                "message" => "chua nhap mat khau",
                "data" => [
                    "id" => "no infomation",
                    "username" => "no infomation",
                    "token" => "no infomation",
                    "avatar" => "no infomation"
                ],

            ]);
        } else if ($password != $credentials->password) {
            //sai mat khau tai khoan
            return response()->json([
                "code" => 9994,
                "message" => "nhap sai mat khau",
                "data" => [
                    "id" => "no infomation",
                    "username" => $phonenumber,
                    "token" => "no infomation",
                    "avatar" => "no infomation"
                ]

            ]);
        } else {
            $credentials->token = $token;
            $credentials->save();
            session([
                'data' => $credentials,
                'token' => $token
            ]);
            return response()->json([
                "code" => 1000,
                "message" => "ban da dang nhap thanh cong",
                "data" => [
                    "id" => $credentials->id,
                    "username" => "chua co",
                    "token" => $token,
                    "avatar" => "chua co"
                ],
            ]);
        }
    } else {
        return response()->json([
            "code" => 9996,
            "message" => "tai khoan khong ton tai",
            "data" => [
                "id" => "no infomation",
                "username" => "chua co",
                "token" => "chua co",
                "avatar" => "chua co"
            ]
        ]);
    }

});

Route::get('/home', function () {

    $sessionToken = session()->get('token');
    $newUser = session()->get('data');
    $user = User::where('token', $sessionToken)->first();
//    $posts = Post::where('author_id',$user->id)->get();
    //neu token cua user = token hien tai cua user tren server,tiep tuc....
    $posts = DB::table('posts')->join('users', 'posts.author_id', '=', 'users.id')->select('posts.*', 'users.name')->get();
    if ($newUser == null || $sessionToken == null) {
        return redirect('/');
    }
    if ($user != null) {
        if ($newUser->name == null && $newUser->link_avatar == null) {
            return redirect('/change_info_after_signup');
        } else {
            if ($posts) {
                return view('logged.home', ["posts" => $posts]);
            }
            return view('logged.home');
        }
    } else {
        return redirect('/');
    }

});

Route::post('/logout', function (Request $request) {
    $credential = User::where('token', session()->get('token'))->first();

    session()->pull('data');
    session()->pull('token');
    //xoa token server
    $credential->token = null;
    $credential->save();
    return response()->json([
        "code" => 1000,
        "message" => "dang xuat thanh cong"
    ]);
});

Route::get('/change_info_after_signup', function () {
    return view('logged.change_info');
});

Route::post('/change_info_after_signup', function (Request $request) {
    $user = User::where('token', $request->token)->first();

    if ($user == null) {
        //khong ton tai user tuc la token dang khong duoc dung
        return redirect('/');
    } else {
        if ($request->input(['username'])) {
            $user->name = $request->input(['username']);
            if ($request->input(['avatar'])) {
                $user->avatar = $request->input(['avatar']);
            }
            $user->touch();
            $user->save();
            session()->put("data", $user);
            return response()->json([
                "code" => 1000,
                "message" => "cap nhat thong tin thanh cong",
                "data" => [
                    "id" => $user->id,
                    "username" => $user->name,
                    "phonenumber" => $user->phonenumber,
                    "created" => $user->created_at,
                    "avatar" => $user->avatar
                ]
            ]);
        } else {
            return response()->json([
                "code" => 1004,
                "message" => "username khong hop le"
            ]);
        }
    }
//    if($request->token != $user->token){
//        return response()->json([
//           "code"=>1004,
//           "message"=>"ma token sai hoac thieu" ,
//        ]);
//    }

});

Route::post('/add_post', function (Request $request) {
    $credential = User::where('token', session()->get('token'))->first();
    if ($credential) {
        $post = new Post();
        $post->described = $request->input('described');
        $post->author_id = $credential->id;
        $post->media = $request->input('image');

        if ($post->described != null || $post->media != null) {
            $post->save();
        } else if ($post->described != null && $post->media != null) {
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

});

Route::get('/get_post/{id}', function ($id) {
    $post = Post::find($id);
    $comments = $post->hasCmts;
    return view('logged.post.view',['post'=>$post,'comments'=>$comments]);

});

Route::post('/get_post', function (Request $request) {


    $post = Post::find($request->input('pid'));
    $user = User::find($post->author_id);
    $comments = $post->hasCmts;

    if ($request->input('token') != $user->token) {
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
            "is_liked" => "",
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
});

Route::post('/like',function(){
    $post = Post::find($request->input('pid'));
    $credential = User::where('token',session()->get('token'))->first();
    
    //tc2-sai token
    if($credential == null){
        return redirect('/home');
    }

    if($post != null && $credential != null){
        //sai tieu chuan hoac quoc gia
        if($post->banned == 1 || $post->banned == 2){
            return response()->json([
                "code"=>1010,
                "message"=>"Bai viet da bi xoa",
            ]);
            //xoa bai viet
        }


        //tc1-ok
        return response()->json([
            "code"=>1000,
            "message"=>"OK",
            "data"=>[
                "like"=>"Chua biet lay dau",
            ]
        ]);
    }
    //tc6-dung ma phien , sai id bai viet
    if($post == null && $credential != null){
        return response()->json([
            'code'=>9992,
            'message'=>'Bai viet khong ton tai',
            'data'=>null
        ]);
    }

});

Route::post('/get_comment',function(Request $request){
    $credential = User::where('token',session()->get('token'))->first();
    $post=Post::find($request->input('pid'));
    //chua co model table comment
    $onPost = onPost::find($request->input('pid'));
    $index = $request->input('index');
    $count = $request ->input('count');

    //dung tat ok
    if($credential != null && $post != null && $index == true && $count == true){
        return response()->json([
            "code"=>1000,
            "message"=>"OK",
            "data"=>[
                "id"=>$onPost->on_post,
                "comment"=>$onPost->content,
                "created"=>$onPost->created_at,
                "poster" => [
                    "id"=>$onPost->from_user,
                    "name"=>"",
                    "avatar"=>""
                ]
                    ]
        ]);
    }

    if($credential == null){
        return redirect("home");
    }
});
