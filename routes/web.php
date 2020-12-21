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
    $user = new User();
    $user->phonenumber = $request->input('phone');
    $user->password = $request->input('pass');

    $phoneNumber = $request->input('phone');
    $oneNum = substr($phoneNumber, 0, 1);

//    $user->uuid = $request->input('uuid');
    $duplicate = User::where('phonenumber', $user->phonenumber)->first();

    //kiem tra sdt null
    if ($request->input('phone') == null) {
        //neu chua nhap sdt
        return response()->json([
            "code" => "error",
            "message" => "ban chua nhap so dien thoai",
            "data" => $request->all()
        ]);
    }

    //ktra dinh dang
    if (strlen($phoneNumber) != 10 || $oneNum != '0') {
        return response()->json([
            "code" => 1004,
            "message" => "Sai định dạng số điện thoại",
            "data" => $request->all()
        ]);
    }

    //kiem tra null pass
    if ($request->input('pass') == null) {
        // neu chua nhap mk
        return response()->json([
            "code" => "error",
            "message" => "ban chua nhap mat khau",
            "data" => $request->all()
        ]);
    }

    //kiem tra sdt trung mk
    if ($request->input('phone') == $request->input('pass')) {
        //neu mk trung sdt
        return response()->json([
            "code" => "error",
            "message" => "ban da nhap sodien thoai trung mat khau",
            "data" => $request->all(),
            "user" => $user
        ]);
    }

    //kiem tra trung lap
    if ($duplicate) {
        if ($duplicate != null) {
            //kiem tra trung lap sdt
            return response()->json([
                "code" => 9996,
                "message" => "Người dùng đã tồn tại",
                "data" => $request->all()
            ]);
        }

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
    $sessionToken = session()->get('token'); // token phien dang nhap
    $newUser = session()->get('data'); //du lieu user trong phien
    $user = User::where('token', $sessionToken)->first(); //nguoi dung hien tai dang co phien dung voi db
    //neu token cua user = token hien tai cua user tren server,tiep tuc....
    $posts = DB::table('posts')->join('users', 'posts.author_id', '=', 'users.id')->select('posts.*', 'users.name')->get();
    //lay ra posts de dang len trang chu

    //khong co token//k co user = ve trang dang nhap
    if ($newUser == null || $sessionToken == null) {
        //
        return redirect('/');
    }

    //neu dung la user hien tai
    if ($user != null) {
        //change-info
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

});

Route::get('/get_post/{id}', function ($id) {
    if (!session()->get('data')) {
        return redirect('/');
    }
    $post = Post::find($id);
    $comments = $post->hasCmts;
    return view('logged.post.view', ['post' => $post, 'comments' => $comments]);
});

Route::post('/get_post', function (Request $request) {
    $post = Post::find($request->input('pid'));
    $user = User::find($post->author_id);
    $currentUser = User::where('token', session()->get("token"))->first();
    $comments = $post->hasCmts;

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

Route::get('/edit_post/{id}', function ($id) {
    $currentUser = User::where('token', session()->get("token"))->first();

    if (!$currentUser) {
        return redirect('/');
    }

    $post = Post::find($id);
    return view('logged.post.edit', ['post' => $post]);
});

Route::post('/edit_post', function (Request $request) {
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
});

Route::post('/delete_post', function (Request $request) {
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
});

Route::post('/report_post', function (Request $request) {
    $post = Post::find($request->input('id'));
    $currentUser = User::where('token', session()->get("token"))->first();
    if (!$currentUser) {
        return redirect('/');
    }

    return response()->json([
        "code" => 1000,
        "message" => "bai viet da duoc bao cao"
    ]);
});


Route::post('/like', function (Request $request) {
    $post = Post::find($request->input('id'));
    $credential = User::where('token', session()->get('token'))->first();
    $likes = $post->hasLikes;
    //tc2-sai token
    if ($credential == null) {
        return redirect('/home');
    }

    if ($post != null && $credential != null) {
        //sai tieu chuan hoac quoc gia
        if ($post->banned == 1 || $post->banned == 2) {
            return response()->json([
                "code" => 1010,
                "message" => "Bai viet da bi xoa",
            ]);
            //xoa bai viet
        }


        //tc1-ok
        return response()->json([
            "code" => 1000,
            "message" => "OK",
            "data" => [
                "like" => sizeof($likes),
            ]
        ]);
    }
    //tc6-dung ma phien , sai id bai viet
    if ($post == null && $credential != null) {
        return response()->json([
            'code' => 9992,
            'message' => 'Bai viet khong ton tai',
            'data' => null
        ]);
    }

});

Route::post('/get_comment', function (Request $request) {
    $credential = User::where('token', session()->get('token'))->first();
    $post = Post::find($request->input('pid'));
    //chua co model table comment
    $onPost = onPost::find($request->input('pid'));
    $index = $request->input('index');
    $count = $request->input('count');

    //dung tat ok
    if ($credential != null && $post != null && $index == true && $count == true) {
        return response()->json([
            "code" => 1000,
            "message" => "OK",
            "data" => [
                "id" => $onPost->on_post,
                "comment" => $onPost->content,
                "created" => $onPost->created_at,
                "poster" => [
                    "id" => $onPost->from_user,
                    "name" => "",
                    "avatar" => ""
                ]
            ]
        ]);
    }

    if ($credential == null) {
        return redirect("home");
    }
});


//api search
Route::post('/search', function (Request $request) {
    $token = $request->input('token');
    $keyword = $request->input('keyword');
    $user_id = $request->input('user_id');
    $index = $request->input('index');
    $count = $request->input('count');

    $credential = User::where('token', $token)->first();
    $check_user = DB::select('select * from users where id = ?', $user_id);


    $list_post = DB::select('select * from posts');
    $posts = [];

    if ($credential != null && $check_user != null && is_string($index) && is_string($count)) {

        //check xem key co trong described khong
        for ($i = 0; $i < count($list_post); $i++) {

            //truong author_id bi loi -tc7
            if ($ck = DB::select('select id from users where id = ?', $list_post[$i]->author_id) == null) {
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
        if (!empty($posts)) {
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

    //sai ma token day login -tc2
    if ($credential == null) {
        return redirect("/home");
    }

    //dung ma phien nhung sai id - tc5
    if ($credential != null && $check_user == null) {
        return response()->json([
            "code" => "gi do khong nho :v",
            "message" => "Bạn không phải bạn :v",
        ]);
    }

    //dung tham so nhung k co tham so keyword -tc6
    if ($keyword == null) {
        return response()->json([
            "code" => "Loi tham so",
        ]);
    }

    //tham so index va count bi loi - tc14
    if ($index == null || $count == null) {
        return response()->json([
            "code" => "lỗi sai giá trị dữ liệu tham số",
            "message" => "Tham số index hoặc count bị lỗi"
        ]);
    }

});

//get_saved_search
Route::post('/get_saved_search', function (Request $request) {
    $token = $request->input('token');
    $index = $request->input('index');
    $count = $request->input('count');

    //Khả năng phải tạo thêm 1 table để lưu key word
    $id_user = DB::select('select id from users where token = ?', $token)[0];
    $list_keyword = DB::select('select id, keyword,created_at from keyword where author_id = ?', $id_user);
    $credential = User::where('token', $token)->first();
    //Thanh cong
    if ($token != null && $index != null && $count != null && !empty($list_keyword)) {
        return response()->json([
            "code" => 1000,
            "message" => "OK",
            "data" => $list_keyword,
        ]);
    };

    //sai token -tc2
    if ($credential == null) {
        return redirect("/home");
    }

    //k co gia tri tra ve tc3
    if (empty($list_keyword)) {
        return response()->json([
            "code" => "loi",
            "message" => "Khong tim thay ket qua nao",
        ]);
    }
});

//del_saved_search
Route::post('/del_saved_search', function (Request $request) {
    $token = $request->input('token');
    $search_id = $request->input('search_id');
    $all = $request->input('all');
    $credential = User::where('token', $token)->first();
    $id_user = DB::select('select id from users where token = ?', $token)[0];
    $check_sid = DB::select('select id from keyword where id = ?', $search_id);

    //xoa tat ca
    if ($all == "1" && $credential != null) {

        //k co lich su tim kiem -tc8
        if ($check_sid == null) {
            return response()->json([
                "code" => "ma loi",
                "message" => "Khong co du lieu tim kiem",
            ]);
        };

        DB::delete('delete from users where author_id = ?', $id_user);
        return response()->json([
            "code" => 1000,
            "message" => "OK",
        ]);
    };

    //sai phien maybe
    if ($credential == null) {
        return redirect("/home");
    }
    //k co search id trong history - tc3
    if ($credential != null && $all == "0") {

        if ($check_sid == null) {
            return response()->json([
                "code" => 1000,
                "message" => "Sai giá trị của dữ liệu tìm kiếm",
            ]);
        }

        //khong co tham so search_id -tc10
        if ($search_id == null) {
            return response()->json([
                "code" => "ma loi tham so k hop le",
                "message" => "",
            ]);
        }
    }

    //search_id la tham so khong hop le -tc5
    if (is_int($search_id)) {
        return response()->json([
            "code" => "loi",
            "message" => "Tham so khong hop le",
        ]);
    }
});

Route::post('/change_password', function (Request $request) {
    $currentUser = User::where('token', $request->input('token'))->first();
    $old = $request->input('password');
    $new = $request->request('new_password');
    $userCheckPass = User::where('password', $old)->first();


    //token dung nhung nhap sai mat khau cu
    if ($userCheckPass == null) {
        return response()->json([
            "code" => "...",
            "messsage" => "nhập sai mật khẩu cũ",
            "data" => $currentUser
        ]);
    }
    if ($currentUser == null) {
        return redirect('/');
    }

    $currentUser->password = $new;
    $currentUser->touch();
    $currentUser->save();
    return response()->json([
        "code" => 1000,
        "messsage" => "thay đổi mật khẩu thành công",
        "data" => $currentUser
    ]);
});

Route::post('/get_user_info/{id}', function (Request $request, $id) {
    $currentUser = User::where('token', $request->input('token'))->first();
    if ($currentUser == null) {
        return redirect('/');
    }
    if ($id == null) {
        return response()->json([
            "code" => 1000,
            "message" => "lấy thông tin người dùng thành công",
            "data" => [
                "id" => $currentUser->id,
                "username" => $currentUser->name,
                "created" => $currentUser->created_at,
                "description" => "...",
                "avatar" => "...",
                "link" => "/get_user_info/" . $currentUser->id,
                "address" => "...",
                "city" => "...",
                "country" => "...",
                "listing" => "...",
                "is_friend" => 0,
                "online" => 1
            ]
        ]);
    } else {
        $user = User::find($request->input('user_id'));
        if($user != null){
            return response()->json([
                "code" => 1000,
                "message" => "lấy thông tin người dùng thành công",
                "data" => [
                    "id" => $user->id,
                    "username" => $user->name,
                    "created" => $user->created_at,
                    "description" => "...",
                    "avatar" => "...",
                    "link" => "/get_user_info/" . $user->id,
                    "address" => "...",
                    "city" => "...",
                    "country" => "...",
                    "listing" => "...",
                    "is_friend" => 0,
                    "online" => 1
                ]
            ]);
        } else {
            return response()->json([
                "code" => 9999,
                "message" => "lấy thông tin người dùng không thành công",
                "data" => "null"
            ]);
        }

    }
});

Route::post('/set_user_info',function (Request $request){
    $currentUser = User::where('token', $request->input('token'))->first();

    if ($currentUser == null) {
        return redirect('/');
    }
    DB::table('user_infos')->insert([
        'description'=>$request->input('description'),
        'avatar'=>$request->input('avatar'),
        'address'=>$request->input('address'),
        'city'=>$request->input('city'),
        'country'=>$request->input('country'),
        'cover_image'=>$request->input('cover_image'),
        'link'=>'/get_user_info'.$currentUser->id
    ]);
});




