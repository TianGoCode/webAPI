<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
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
Route::view('/test', 'logged.homepage');


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

    if ($duplicate) {
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
    if ($credentials) {
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

    //neu token cua user = token hien tai cua user tren server,tiep tuc....
    if ($user) {
        if ($newUser->name == null && $newUser->link_avatar == null) {
            return redirect('/change_info_after_signup');
        } else {
            return view('layout');//tam thoi la view logout
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
    return view('logged.homepage');
});

Route::post('/change_info_after_signup', function (Request $request) {
    $user = User::where('token', $request->token)->first();
    //chua co check username
    if (!$user) {
        return redirect('/');
    } else {
        if ($request->input(['username'])) {
            $user->name = $request->input(['username']);
            if ($request->input(['avatar'])) {
                $user->avatar = $request->input(['avatar']);
            }
            $user->touch();
            $user->save();
            session()->put("data",$user);
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
