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
        "data" => $request->all(),
        "link" => $path
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
Route::post('/del_saved_search', [App\Http\Controllers\PostController::class, 'del_saved_search']);

Route::post('/change_password', [App\Http\Controllers\PostController::class, 'change_password']);

Route::post('/get_user_info/{id}', [App\Http\Controllers\UserController::class, 'get_user_info']);

Route::post('/set_user_info', [App\Http\Controllers\UserController::class, 'set_user_info']);


Route::post('/set_accept_friend', [App\Http\Controllers\UserController::class, 'set_accept_friend']);

//Get_list_suggested_friends
Route::post('/get_list_suggested_friends', [App\Http\Controllers\UserController::class, 'get_list_suggested_friends']);

Route::post('/get_request_friend', [App\Http\Controllers\UserController::class, 'get_request_friend']);

Route::post('/get_user_friends', [App\Http\Controllers\UserController::class, 'get_user_friends']);

Route::post('/set_request_friend', [App\Http\Controllers\UserController::class, 'set_request_friend']);


//get_list_blocks
Route::post('get_list_blocks', [App\Http\Controllers\UserController::class, 'get_list_blocks']);

//Get_push_settings - lại phải tạo thêm table setting rồi
Route::post('/get_push_settings', [App\Http\Controllers\HomeController::class, 'get_push_settings']);

//set block
Route::post('set_block', [App\Http\Controllers\UserController::class, 'set_block']);






