<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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
    //test
    // $rules = [
    //     'phone' => 'required|numeric|phone_number|size:10',
    //     'pass' => 'required|min:6|max:8'
    // ];

    // $messages = [
    //     'phone.required' => 'Sdt khong trong',
    //     'phone.numeric' => 'Phai la so',
    //     'phone.phone_number' => 'Bat dau bang so 0',
    //     'phone.size' => 'Phai 10 so',
    //     'pass.required' => 'Pass khong trong',
    //     'pass.min' => 'Khong nho hon 6',
    //     'pass.max' => 'Khong dc lon hon 8',
    // ];

    // $validator = Validator::make($request -> all(), $rules, $messages);

    // return response()->json([
    //     'mess' => $messages,
    //     'rules' => $rules,
    //     'validate' => $validator->fails()
    // ]);

    // if($validator -> fails()){
    //     return redirect('signup')->withErrors($validator)->withInput();
    // };


    // lay ra thong tin request duoc gui len
    // $user = new User();
    // $user->phonenumber = $request->input('phone');
    // $user->password = bcrypt($request->input('pass'));
//    $user->uuid = $request->input('uuid');
<<<<<<< HEAD
    
    // $duplicate = User::where('phonenumber', $user->phonenumber)->first();

    // if ($duplicate) {

    //     //kiem tra trung lap sdt
    //     return response()->json([
    //         "code" => 9996,
    //         "message" => "sdt ban dang ky da trung",
    //         "data" => $request->all()
    //     ]);
    // }
    // if ($request->input('phone') == null) {
    //     //neu chua nhap sdt
    //     return response()->json([
    //         "code" => "error",
    //         "message" => "ban chua nhap so dien thoai",
    //         "data" => $request->all()
    //     ]);
    // }
    // if ($request->input('pass') == null) {
    //     // neu chua nhap mk
    //     return response()->json([
    //         "code" => "error",
    //         "message" => "ban chua nhap mat khau",
    //         "data" => $request->all()
    //     ]);
    // }
    // if ($request->input('phone') == $request->input('pass')) {
    //     //neu mk trung sdt
    //     return response()->json([
    //         "code" => "error",
    //         "message" => "ban da nhap sodien thoai trung mat khau",
    //         "data" => $request->all()
    //     ]);
    // }

    // $user->save();

    // return response()->json([
    //     "code" => 1000,
    //     "message" => "dang ky thanh cong",
    //     "data" => $request->all()
    // ]);
=======

    $duplicate = User::where('phonenumber', $user->phonenumber)->first();

    if ($duplicate) {

        //kiem tra trung lap sdt
        return response()->json([
            "code" => 9996,
            "message" => "sdt ban dang ky da trung",
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
>>>>>>> 80ea4364a63bff1344a354938aeffc132256b8eb

});


Route::get('/login', function () {
    return view('logIn');
});

Route::post('/login', function (Request $request) {

    $data = [
        'phone' => $request->phoneIn,
        'pass' => $request->passIn
    ];

    $request->validate([
        'phoneIn' => 'required|min:4',
        'passIn' => 'required'
    ]);

    if (Auth::attempt($data)) {
        dd('Đăng nhập thành công');
    } else {
        dd('TK hoặc MK chưa đúng');
    }
});
