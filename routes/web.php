<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
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

Route::get('/', function () {
    return view('welcome');
});
//Route::get('/{name}', function ($name) {
//    return $name;
//});


Route::get('/home', function () {
    return view('home');
});

Route::get('/signin', function (){
    return view('signIn');
});

Route::post('/signin',function(Request $request){
   $user = new User();
   $user->phone = $request->input('phone');
   $user->pass = $request->input('pass');
   $user->uuid = $request->input('uuid');
   
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

