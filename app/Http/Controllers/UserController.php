<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $name = $request->input('name');
        $pass = $request->input('password');
        $phoneNumber = $request->input('phonenumber');
        if($pass == $phoneNumber){
            return response()->json([
                "code" => "9995",
                "message" => "sdt trung voi mat khau dang ky"
            ]);
        } elseif (strpos($phoneNumber,"0") == false){
            return response()->json([
                "code" => "9995",
                "vi tri cua so 0 trong so dien thoai" =>strpos($phoneNumber,"0"),
                "sdt"=>$phoneNumber,
                "co phai la sdt"=>is_numeric((string)$phoneNumber),
                "message" => "sai ddinhj dang sdt"
            ]);
        }

        else {
//            $user = new User();
//            $user->name = $name;
//            $user->password = $pass;
//            $user->phoneNumber = $phoneNumber;
//            $user->save();
            return response()->json([
                "code" => "1000",
//                "user" => $user
            ]);
        }

    }
}
