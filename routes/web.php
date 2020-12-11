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

Route::get('/signup', function (){
    return view('signUp');
});

Route::post('/signup',function(Request $request){
   $user = new User();
   $user->phone = $request->input('phone');
   $user->pass = bcrypt($request->input('pass'));
   $user->uuid = Uuid::generate()->string;
   
    $user->save();

   return response()->json([
       "user vua dang ky"=>$user,
       "thong tin vua nhan"=>$request->all()





       //2 nhiem vu cua dang ky : luu nguoi dung va testcase : test case dung het thi moi luu nguoi dung !
   ]);
});

//Route::post('/signup',function (Request $request){
//    return response()->json([
//        'code'=>'1000',
//        'response'=>$request->all()
//    ]);
//});


//Route::post('/home',function (Request $request){
////    return view('login')->with(['request'=>$request->all()]);
//    return response()->json([
//        'code'=>'1000',
//        'response'=>$request->all()
//    ]);
//});

Route::get('/login',function() {
    return view('logIn');
});

Route::post('/login', function(Request $request) {

    $data = [
            'phone'=> $request->phoneIn,
            'pass' => $request->passIn
    ];
   
    $request->validate([
            'phoneIn' => 'required|min:4',
            'passIn' => 'required'
    ]);

         if(Auth::attempt($data)){
            dd('Đăng nhập thành công');
        }
         else {
            dd('TK hoặc MK chưa đúng');
        }
});