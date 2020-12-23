<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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


}
