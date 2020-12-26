<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;


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


Route::view('/anh', 'logIn');
Route::post('/anh', function (Request $request) {
    $user = new User();
    $path = $request->file('image')->store('public');

    return response()->json([
        "data"=>$request->all(),
        "link"=>$path
    ]);
});

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


Route::post('/signup', [App\Http\Controllers\SignupController::class, 'signup']);

Route::post('/login', [App\Http\Controllers\LoginController::class, 'login']);

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

Route::post('/logout', [App\Http\Controllers\HomeController::class, 'logout']);

Route::get('/change_info_after_signup', function () {
    $user = User::where('token', session()->get('token'))->where('name', null)->first();
    if ($user) {
        return view('logged.change_info');
    }
    return redirect('/');

});

Route::post('/change_info_after_signup', [App\Http\Controllers\SignupController::class, 'change_info_after_signup']);

Route::post('/add_post', [App\Http\Controllers\PostController::class, 'add_post']);

Route::get('/get_post/{id}', function ($id) {
    if (!session()->get('data')) {
        return redirect('/');
    }
    $post = Post::find($id);
    $comments = $post->hasCmts;
    $likes = DB::table('like_post')->select('*')->where('post_id', '=', $post->id)->get();
    return view('logged.post.view', ['post' => $post, 'comments' => $comments, 'likes' => sizeof($likes)]);
});

Route::post('/get_post', [App\Http\Controllers\PostController::class, 'get_post']);

Route::get('/edit_post/{id}', function ($id) {
    $currentUser = User::where('token', session()->get("token"))->first();

    if (!$currentUser) {
        return redirect('/');
    }

    $post = Post::find($id);
    return view('logged.post.edit', ['post' => $post]);
});

Route::post('/edit_post', [App\Http\Controllers\PostController::class, 'edit_post']);

Route::post('/delete_post', [App\Http\Controllers\PostController::class, 'delete_post']);

Route::post('/report_post', [App\Http\Controllers\PostController::class, 'report_post']);

Route::post('/like', [App\Http\Controllers\PostController::class, 'like']);

Route::post('/get_comment', [App\Http\Controllers\CommentController::class, 'get_comment']);

Route::post('/set_comment', [App\Http\Controllers\CommentController::class, 'set_comment']);

Route::post('/search', [App\Http\Controllers\PostController::class, 'report_post']);

Route::post('/get_saved_search', [App\Http\Controllers\PostController::class, 'get_saved_search']);

//del_saved_search
Route::post('/del_saved_search', function (Request $request) {
    $token = $request->input('token');
    $search_id = $request->input('search_id');
    $all = $request->input('all');
    $credential = User::where('token', $token)->first();
    $id_user = DB::select('select id from users where token = ?', $token)->first();
    $check_sid = DB::select('select id from keyword where id = ?', $search_id);

    //sai phien 
    if ($credential == null) {
        return redirect("/");
    }

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
        if ($user != null) {
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

Route::post('/set_user_info', function (Request $request) {
    $currentUser = User::where('token', $request->input('token'))->first();

    if ($currentUser == null) {
        return redirect('/');
    }
    DB::table('user_infos')->insert([
        'description' => $request->input('description'),
        'avatar' => $request->input('avatar'),
        'address' => $request->input('address'),
        'city' => $request->input('city'),
        'country' => $request->input('country'),
        'cover_image' => $request->input('cover_image'),
        'link' => '/get_user_info' . $currentUser->id
    ]);
});

//set_accept_friend
Route::post('/set_accept_friend', function (Request $request) {
    $token = $request->input('token');
    $user_id = $request->input('user_id');
    $is_accept = $request->input('is_accept');
    $currentUser = User::where('token', $request->input('token'))->first();
    $check_user = DB::select('select id form users where id = ?', $user_id);

    if ($token != null && $user_id != null && $is_accept != null) {

        //user_id k phai 0 hoac 1 -tcc8
        if ($is_accept != 0 || $is_accept != 1) {
            return response()->json([
                "code" => 1004,
                "message" => "Dữ liệu truyền vào không hợp lệ",
            ]);
        }

        return response()->json([
            "code" => 1000,
            "message" => "OK",
        ]);
    }

    if ($currentUser == null) {
        return redirect('/');
    }
    //k co tham so user id hoc k chuan -tc5
    if ($user_id == null && is_string($user_id) && $currentUser != null) {
        return response()->json([
            "code" => "1003",
            "message" => "Kiểu tham số không đúng đắn",
        ]);
    }
    //Neu nguoi dung k ton tai -tc6
    if ($check_user == null) {
        return response()->json([
            "code" => 9995,
            "message" => "Người dùng không tồi tại",
        ]);
    }
});

//Get_list_suggested_friends
Route::post('/get_list_suggested_friends', function () {
    $token = $request->input('token');
    $index = $request->input('index');
    $count = $request->input('count');
    $currentUser = User::where('token', $request->input('token'))->first();
    $id_user = $currentUser->id;
    $list_user = DB::select('select * from users where id != ?', $id_user);

    //ok -tc1
    if ($currentUser != null && is_string($index) && is_string($index) && is_string($count)) {
        return response()->json([
            "code" => 1000,
            "message" => "OK",
            "data" => $list_user,
        ]);
    }

//sai mã phiên đăng nhập -tc2
    if ($token == null && is_string($token)) {
        return redirect('/');
    }

    if ($currentUser != null && $index == null && $count == null && is_string($index) && is_string($count)) {
        return response()->json([
            "code" => 1004,
            "message" => "Lỗi tham số không hợp lệ",
        ]);
    }

});

//set request friend - chưa xong chưa biết lấy ở đâu
Route::post('/get_request friend', function () {
    $token = $request->input('token');
    $user_id = $request->input('user_id');
    $credential = User::where('token', $token)->first();
    $requested_friends = 0;

    if ($credential != null && $user_id != null) {

        //không tìm thấy ng dùng -tc5
        if (User::where('id', $user_id)->first() == null) {
            return response()->json([
                "code" => 9995,
                "message" => "Không có người dùng này",
            ]);
        }

        //Truyền đúng tham số nhưng id = id chính tk -tc6
        if ($credential->id == $user_id) {
            return response()->json([
                "code" => 1003,
                "message" => "Chính là bạn"
            ]);
        }

        //requested friends k phải số hoặc âm -tc7
        if (!is_int($requested_friends)) {
            return response()->json([
                "code" => 1000,
                "message" => "OK",
                "data" => 0,
            ]);
        }

        return response()->json([
            "code" => 1000,
            "message" => "OK",
            "data" => $requested_friends,
        ]);
    }

    if ($credential == null) {
        return redirect('/');
    }


});

//get_list_blocks
Route::post('get_list_blocks', function (Request $request) {
    $token = $request->input('token');
    $index = $request->input('index');
    $count = $request->input('count');
    $credential = User::post('token', $token)->first();
    $user_id = $credential->id;
    $block_list = DB::select('select block_id from black_list where user_id = ?', $user_id);
    $data = [];

    //sai mã phiên -tc2
    if ($token == null || $credential == null) {
        return redirect('/');
    }

    //ok
    if ($credential != null && $block_list != null) {

        //k có tham sô index và count -tc5
        if ($index == null || $count == null || !is_int($index) || !is_int($count)) {
            return response()->json([
                "code" => 1004,
                "message" => "Giá trị tham số không hợp lệ"
            ]);
        }

        //them nam,avatar vao data
        for ($i = 0; $i < count($block_list); $i++) {
            $user = User::where('id', $block_list[$i])->first();
            $block_list[$i]->name = $user->name;
            $block_list[$i]->avatar = $user->link_avatar;
            array_push($data, $block_list[$i]);

            return response()->json([
                "code" => 1000,
                "message" => "OK",
                "data" => $data
            ]);
        }
    }

});

//Get_push_settings - lại phải tạo thêm table setting rồi
Route::post('/get_push_settings', function (Request $request) {
    $token = $request->input('token');
    $credential = User::where('token', $token)->first();
    //lấy data từ settings -chưa có
    $data_settings = [];

    if ($credential) {

        //nếu ng dùng chưa có trong table này thì thiết lập 1 cả -tc7
        if (abc) {
            return response()->json([
                "code" => 1000,
                "message" => "Thiết lập lần đầu",
                "data" => [
                    "like_comment" => 1,
                    "from_friends" => 1,
                    "requested_friend" => 1,
                    "suggested_friend" => 1,
                    "birthday" => 1,
                    "video" => 1,
                    "report" => 1,
                    "sound_on" => 1,
                    "notification_on" => 1,
                    "vibrant_on" => 1,
                    "led_on" => 1,
                ]
            ]);
        }

        return response()->json([
            "code" => 1000,
            "message" => "OK",
            "data" => $data_settings,
        ]);
    }

    if ($credential != null && $token != null) {
        return redirect('/');
    }

});

//set_push_settings - khó hiểu k làm
Route::post('set_push_settings', function (Request $request) {

});


//set block
Route::post('set_block', function (Request $request) {
    $token = $request->input('token');
    $user_id = $request->input('user_id');
    $type = $request->input('type');
    $credential = User::post('token', $token)->first();
    $check_user = User::find('id', $user_id);

    //Dữ liệu trong table xem đang chặn hay không chặn
    $check_band = '';

    if ($credential != null && ($type == 0 || $type == 1)) {

        //chinh la chu tk -tc5
        if ($check_user->id == $credential->id) {
            return response()->json([
                "code" => "loi",
                "message" => "Ta là ta"
            ]);
        }

        //ng bi chan khong ton tai -tc6
        if ($check_user == null) {
            return response()->json([
                "code" => "loi",
                "message" => "NGười dùng không tồn lại",
            ]);
        }

        //ok
        if ($check_user != null) {

            //di chan 1 nguoi chua bao gio chan or bo chan -tc9
            if ($type == $check_band) {
                return response()->json([
                    "code" => 1003,
                    "message" => "Chưa chặn người dùng này hoặc chưa đã chặn",
                ]);
            }


            return response()->json([
                "code" => 1000,
                "message" => "OK",
            ]);
        }
    }

    //sai phien -tc2
    if ($credential == null && $token == null) {
        return redirect('/');
    }
});

//check new version
Route::post('check_new_version', function () {
    //Khó quá - bỏ :D
});

//get notification
Route::post('get_notification', function (Request $request) {
    $token = $request->input('token');
    $index = $request->input('index');
    $count = $request->input('count');
    $credential = User::where('token', $token)->first();


});

//set comment - xem lại cho t cái này nữa, chưa hiểu tác dụng của nó lắm - t làm ở dưới là lấy 1 list comment mới về



