<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    public function logout(Request $request) {
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
    }

    public function set_push_setting(Request $request) {
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

    }
}
