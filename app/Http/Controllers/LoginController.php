<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;


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

class LoginController extends Controller
{
    public function login(Request $request)
    {
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

    }
}
