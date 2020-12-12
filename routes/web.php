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

Route::get('/wel', function () {
    return view('welcome');
});
//Route::get('/{name}', function ($name) {
//    return $name;
//});


Route::get('/home', function () {
    return view('home');
});

Route::get('/signup', function () {
    return view('signUp');
});

Route::post('/signup', function (Request $request) {
<<<<<<< HEAD
  

//    lay ra thong tin request duoc gui len
=======


    // lay ra thong tin request duoc gui len
>>>>>>> 7ea9bd76c9b2c1a7b2462865c32dec259ba495d8
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


Route::get('/login', function () {
    return view('logIn');
});

Route::post('/login', function (Request $request) {
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
                    "token" => $request->input('token'),
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
                    "token" => $request->input('token'),
                    "avatar" => "no infomation"
                ]

            ]);
        } else {
            return response()->json([
                "code" => 1000,
                "message" => "ban da dang nhap thanh cong",
                "data" => [
                    "id" => $credentials->id,
                    "username" => "chua co",
                    "token" => "chua co",
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
            ],

        ]);
    }


});
