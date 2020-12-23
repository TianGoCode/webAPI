<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class SignupController extends Controller
{
    public function signup(Request $request)
    {
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
    }

    public function change_info_after_signup(Request $request)
    {
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

    }

    public function get_verify_code(Request $request)
    {
        $phonenumber = $request->input('phonenumber');
        $code = generateRandomString(5);
        $credentials = DB::table('verified_phonenumber')->select("*")->where('phonenumber','=',$phonenumber);
        $checkRegistered = DB::table('users')->select('phonenumber')->where('phonenumber', '=', $phonenumber)->first();

        //check thoi diem gui len?

        if ($checkRegistered != null) {
            return response()->json([
                "code" => 1004. / .9996,
                "message" => "Số điện thoại đã được đăng ký"
            ]);
        }

        if (strlen($phonenumber) != 10 || substr($phonenumber, 0, 1) != '0') {
            return response()->json([
                "code" => 1004,
                "message" => "Sai định dạng số điện thoại",
            ]);
        }

        return response()->json([
            "code"=>1000,
            'message'=>"gửi ma xác thực thành công"
        ]);
    }

    public function check_verify_code(Request $request)
    {
        $phonenumber = $request->input('phonenumber');
        $code = $request->input('code');
        $credentials = DB::table('verified_phonenumber')->select('*')->where('phonenumber', '=', $phonenumber)->where('code', "=", $code)->first();
        $checkRegistered = DB::table('users')->select('phonenumber')->where('phonenumber', '=', $phonenumber)->first();

        if (strlen($phonenumber) != 10 || substr($phonenumber, 0, 1) != '0') {
            return response()->json([
                "code" => 1004,
                "message" => "Sai định dạng số điện thoại",
                "data" => "null"
            ]);
        }

        if ($credentials == null) {
            return response()->json([
                "code" => 1004. / .9995,
                "message" => "số điện thoại không có trong danh xác xác thực",
                "data" => "null"
            ]);
        }

        if ($checkRegistered != null) {
            return response()->json([
                "code" => 1004. / .9996,
                "message" => "Số điện thoại đã được đăng ký",
                "data" => "null"
            ]);
        }

        return response()->json([
            "code" => 1000,
            "message" => "xác thức mã thành công",
            "data" => $credentials
        ]);
    }
}
