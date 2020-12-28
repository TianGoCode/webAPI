<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function store(Request $request)
    {
        $name = $request->input('name');
        $pass = $request->input('password');
        $phoneNumber = $request->input('phonenumber');
        if ($pass == $phoneNumber) {
            return response()->json([
                "code" => "9995",
                "message" => "sdt trung voi mat khau dang ky"
            ]);
        } elseif (strpos($phoneNumber, "0") == false) {
            return response()->json([
                "code" => "9995",
                "vi tri cua so 0 trong so dien thoai" => strpos($phoneNumber, "0"),
                "sdt" => $phoneNumber,
                "co phai la sdt" => is_numeric((string)$phoneNumber),
                "message" => "sai ddinhj dang sdt"
            ]);
        } else {
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

    public function get_request_friend(Request $request)
    {
        $token = $request->input('token');
        $index = $request->input('index');
        $count = $request->input('count');
        $credential = User::where('token', $token)->first();
        $data = DB::table('relationship_list')->join('users', 'relationship_list.user_id2', '=', 'users.id')->select('users.id', 'users.name', 'users.link_avatar')->where('relationship_list.is_requesting', '=', '1')->get();
        // chua count dc so friend chuing
        if ($credential == null) {
            return redirect('/');
        }

        if ($credential->is_banned == 1) {
            return redirect('/');
        }

        if ($data == null) {
            return response()->json([
                'code' => 9994,
                'message' => "lkhông có kết quả trả về",
                'data' => $data
            ]);
        }

        return response()->json([
            'code' => 1000,
            'message' => "lấy danh sách thành công",
            'data' => $data
        ]);
    }

    public function get_user_friends(Request $request)
    {
        $user_id = $request->input('user_id');
        $index = $request->input('index');
        $count = $request->input('count');
        $credential = User::where('token', $request->input('token'))->first();
        if ($credential == null) {
            return redirect('/');
        }

        if ($credential->is_banned == 1) {
            return redirect('/');
        }

        if ($user_id == null) {
            $user_id = $credential->id;
        }

        if ($user_id != $credential->id) {
            if ($user = User::find($user_id)->first()) {
                if ($user == null) {
                    return response()->json([
                        'code' => 9995,
                        'message' => 'lay danh sach ban thanh cong',
                        'data' => 'null'
                    ]);
                }
                if ($user->is_banned == 1) {
                    return redirect('/');
                }
            }
        }

        $data = DB::table('relationship_list')->join('users', 'relationship_list.user_id2', '=', 'users.id')->select('users.id2', 'users.name', 'users.link_avatar')->where('user_id1', '=', $user_id)->where('relationship_list.is_friend', '=', '1')->get();

        return response()->json([
            'code' => 1000,
            'message' => 'lay danh sach ban thanh cong',
            'data' => $data,
            'total' => sizeof($data)
        ]);
    }

    public function set_accept_friend(Request $request)
    {
        $token = $request->input('token');
        $user_id = $request->input('user_id');
        $is_accept = $request->input('is_accept');
        $currentUser = User::where('token', $token)->first();
        $check_user = User::find($user_id);
        $checkFriend = DB::table('relationship_list')->where('user_id1', '=', $currentUser->id)->where('user_id2', '=', $user_id)->where('is_friend', '=', 1);

        if ($currentUser == null) {
            return redirect('/');
        }

        if ($currentUser->is_banned == 1) {
            return redirect('/');
        }

        if ($user_id == null) {
            return response()->json([
                'code' => 1003,
                'message' => 'giá trị tham số có lỗi'
            ]);
        }

        if ($is_accept != 0 || $is_accept != 1) {
            return response()->json([
                'code' => 1004,
                'message' => 'bạn đã kết bạn với người này rồi'
            ]);
        }

        if ($checkFriend != null) {
            return response()->json([
                'code' => 9997,
                'message' => 'bạn đã kết bạn với người này rồi'
            ]);
        }

        if ($check_user == null) {
            return response()->json([
                'code' => 9995,
                'message' => 'không có người dùng này'
            ]);
        }

        DB::table('relationship_list')->insert([
            'user_id1' => $user_id,
            'user_id2' => $check_user->id,
            'is_friend' => 1,
            'created_at' => Carbon::now(),
        ]);
        return response()->json([
            'code' => 1000,
            'message' => 'đã kết bạn'
        ]);

    }

    public function get_list_suggested_friends(Request $request)
    {
        $token = $request->input('token');
        $index = $request->input('index');
        $count = $request->input('count');
        $currentUser = User::where('token', $request->input('token'))->first();
        $id_user = $currentUser->id;
        $list_user = DB::select('select * from relationship_list where user_id2 in (select user_id1 from relationship_list where is_friend = 1) and user_id1=?', $currentUser->id);

        //ok -tc1
        if ($currentUser != null && is_string($index) && is_string($index) && is_string($count)) {
            return response()->json([
                "code" => 1000,
                "message" => "OK",
                "data" => $list_user,
            ]);
        }

//sai mã phiên đăng nhập -tc2
        if ($currentUser == null) {
            return redirect('/');
        }
        if ($currentUser->is_banned == 1) {
            return redirect('/');
        }

        if ($currentUser != null && $index == null && $count == null && is_string($index) && is_string($count)) {
            return response()->json([
                "code" => 1004,
                "message" => "Lỗi tham số không hợp lệ",
            ]);
        }

    }

    public function set_request_friend(Request $request)
    {
        $token = $request->input('token');
        $uid = $request->input('user_id');
        $credential = User::where('token', $token);
        $find = User::find($uid);
        if ($credential == null) {
            return redirect('/');
        }

        if ($find == null) {
            return response()->json([
                'code' => 9995,
                'message' => 'không có người dùng này'
            ]);
        }

        if ($find->is_banned == 1) {
            return response()->json([
                'code' => 1005,
                'message' => 'người dùng đã bị khóa'
            ]);
        }

        if ($uid == $credential->id) {
            return response()->json([
                'code' => 9997,
                'message' => 'bạn không thể kết bạn với bản thân'
            ]);
        }

        DB::table('relationship_list')->where('user_id1', $credential->id)->where('user_id2', $uid)->update(['is_friend' => 1]);
        DB::table('relationship_list')->where('user_id1', $uid)->where('user_id2', $credential->id)->update(['is_friend' => 1]);
        return response()->json([
            'code' => 1000,
            'message' => 'kết bạn thành công'
        ]);

    }

    public function get_list_blocks(Request $request)
    {
        $token = $request->input('token');
        $index = $request->input('index');
        $count = $request->input('count');
        $credential = User::post('token', $token)->first();
        $user_id = $credential->id;
        $block_list = DB::table('relationship_list')->join('users', 'relationship_list.user_id2', '=', 'users.id')->select('users.id', 'users.name', 'users.link_avatar')->where('user_id1', $credential->id)->where('is_block', '=', 1);
        $data = [];

        //sai mã phiên -tc2
        if ($token == null || $credential == null) {
            return redirect('/');
        }

        //ok
        if ($credential != null && $block_list != null) {

            //k có tham sô index và count -tc5
            if ($index == null || $count == null || !is_int($index) || !is_int($count)) {
                return response()->json([
                    "code" => 1004,
                    "message" => "Giá trị tham số không hợp lệ"
                ]);
            }

            //them nam,avatar vao data
            for ($i = 0; $i < count($block_list); $i++) {
                $user = User::where('id', $block_list[$i])->first();
                $block_list[$i]->name = $user->name;
                $block_list[$i]->avatar = $user->link_avatar;
                array_push($data, $block_list[$i]);

                return response()->json([
                    "code" => 1000,
                    "message" => "OK",
                    "data" => $data
                ]);
            }
        }

    }

    public function change_password(Request $request)
    {
        $currentUser = User::where('token', $request->input('token'))->first();
        $old = $request->input('password');
        $new = $request->request('new_password');
        $userCheckPass = User::where('password', $old)->first();

        if ($currentUser == null) {
            return redirect('/');
        }
        if ($currentUser->is_banned == 1) {
            return redirect('/');
        }


        //token dung nhung nhap sai mat khau cu
        if ($currentUser->password != $old) {
            return response()->json([
                "code" => 9993,
                "messsage" => "nhập sai mật khẩu cũ",
                "data" => 'null'
            ]);
        }


        $currentUser->password = $new;
        $currentUser->touch();
        $currentUser->save();
        return response()->json([
            "code" => 1000,
            "messsage" => "thay đổi mật khẩu thành công",
        ]);
    }

    public function set_block(Request $request)
    {
        $token = $request->input('token');
        $user_id = $request->input('user_id');
        $type = $request->input('type');
        $credential = User::where('token', $token)->first();
        $check_user = User::find($user_id);

        //sai phien -tc2
        if ($credential == null && $token == null) {
            return redirect('/');
        }

        //Dữ liệu trong table xem đang chặn hay không chặn
        $check_band = '';

        if ($credential != null && ($type == 0 || $type == 1)) {

            //chinh la chu tk -tc5
            if ($check_user->id == $credential->id) {
                return response()->json([
                    "code" => 9996,
                    "message" => "bạn là chủ tài khoản này"
                ]);
            }

            //ng bi chan khong ton tai -tc6
            if ($check_user == null) {
                return response()->json([
                    "code" => 9994,
                    "message" => "NGười dùng không tồn lại",
                ]);
            }

            //ok

            //di chan 1 nguoi chua bao gio chan or bo chan -tc9
            if ($type == $check_band) {
                return response()->json([
                    "code" => 1003,
                    "message" => "Chưa chặn người dùng này hoặc chưa đã chặn",
                ]);
            }

            DB::table('relationship_list')->where('user_id1', $credential->id)->where('user_id2', $user_id)->update(['is_block' => 1]);
            return response()->json([
                "code" => 1000,
                "message" => "OK",
            ]);

        }


    }

    public function get_user_info(Request $request, $id)
    {
        $currentUser = User::where('token', $request->input('token'))->first();

        if ($currentUser == null) {
            return redirect('/');
        }
        if ($id == null) {
            $info = DB::table('user_info')->where('user_id', $currentUser->id)->first();
            return response()->json([
                "code" => 1000,
                "message" => "lấy thông tin người dùng thành công",
                "data" => [
                    "id" => $currentUser->id,
                    "username" => $currentUser->name,
                    "created" => $currentUser->created_at,
                    "link" => "/get_user_info/" . $currentUser->id,
                    "info" => $info,
                    "is_friend" => 0,
                    "online" => 1
                ]
            ]);
        } else {
            $user = User::find($request->input('user_id'));

            if ($user != null) {
                $info = DB::table('user_info')->where('user_id', $id)->first();
                return response()->json([
                    "code" => 1000,
                    "message" => "lấy thông tin người dùng thành công",
                    "data" => [
                        "id" => $user->id,
                        "username" => $user->name,
                        "created" => $user->created_at,

                        "link" => "/get_user_info/" . $user->id,
                        "info" => $info,
                        "is_friend" => 0,
                        "online" => 1
                    ]
                ]);
            } else {
                return response()->json([
                    "code" => 9999,
                    "message" => "lấy thông tin người dùng không thành công",
                    "data" => "null"
                ]);
            }

        }
    }

    public function set_user_info(Request $request) {
        $currentUser = User::where('token', $request->input('token'))->first();

        if ($currentUser == null) {
            return redirect('/');
        }
        DB::table('user_info')->where('user_id',$currentUser->id)->insert([
            'description' => $request->input('description'),
            'avatar' => $request->input('avatar'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'country' => $request->input('country'),
            'cover_image' => $request->input('cover_image'),
            'link' => '/get_user_info' . $currentUser->id
        ]);
    }
}
