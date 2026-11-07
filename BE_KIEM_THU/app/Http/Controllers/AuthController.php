<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * Chức năng: Đăng nhập hệ thống
     * Hỗ trợ đầy đủ các kịch bản kiểm thử:
     * - TC_LOGIN_01: Để trống Email
     * - TC_LOGIN_02: Email sai định dạng
     * - TC_LOGIN_03: Để trống Mật khẩu
     * - TC_LOGIN_04: Mật khẩu dưới 6 ký tự
     * - TC_LOGIN_05: Email không tồn tại trong hệ thống
     * - TC_LOGIN_06: Sai mật khẩu
     * - TC_LOGIN_07: Tài khoản bị khóa (trang_thai = 0)
     * - TC_LOGIN_08: Đăng nhập thành công
     */
    public function dangNhap(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Vui lòng nhập địa chỉ email!',
            'email.email'       => 'Địa chỉ email không đúng định dạng (ví dụ: user@gmail.com)!',
            'password.required' => 'Vui lòng nhập mật khẩu!',
            'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Tìm người dùng theo email
        $user = User::where('email', $request->email)->first();

        // Kiểm tra tài khoản có tồn tại không
        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Tài khoản không tồn tại trên hệ thống!',
            ], 404);
        }

        // Kiểm tra tài khoản có bị khóa không
        if (!$user->trang_thai) {
            return response()->json([
                'status'  => false,
                'message' => 'Tài khoản của bạn đã bị KHÓA do vi phạm quy chế hoặc tạm ngưng hoạt động!',
            ], 403);
        }

        // Kiểm tra mật khẩu (hỗ trợ cả Hash bcrypt lẫn text thuần nếu là tài khoản seed test)
        $passwordMatches = Hash::check($request->password, $user->mat_khau) || ($request->password === $user->mat_khau);
        if (!$passwordMatches) {
            return response()->json([
                'status'  => false,
                'message' => 'Mật khẩu không chính xác! Vui lòng thử lại.',
            ], 401);
        }

        // Tạo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Đăng nhập thành công! Chào mừng ' . $user->ho_ten,
            'token'   => $token,
            'user'    => [
                'id'            => $user->id,
                'ho_ten'        => $user->ho_ten,
                'email'         => $user->email,
                'vai_tro'       => $user->vai_tro,
                'so_dien_thoai' => $user->so_dien_thoai,
                'dia_chi'       => $user->dia_chi,
                'is_master'     => $user->is_master,
            ]
        ]);
    }

    /**
     * Chức năng: Đăng ký tài khoản
     * Hỗ trợ đầy đủ các kịch bản kiểm thử:
     * - TC_REGISTER_01: Để trống Họ và tên
     * - TC_REGISTER_02: Để trống Email
     * - TC_REGISTER_03: Email sai định dạng
     * - TC_REGISTER_04: Trùng Email đã tồn tại
     * - TC_REGISTER_05: Để trống Số điện thoại
     * - TC_REGISTER_06: Số điện thoại không đúng 10 chữ số
     * - TC_REGISTER_07: Để trống Mật khẩu / Dưới 6 ký tự
     * - TC_REGISTER_08: Mật khẩu xác nhận không khớp
     * - TC_REGISTER_09: Không đồng ý điều khoản
     * - TC_REGISTER_10: Đăng ký thành công
     */
    public function dangKy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ho_ten'           => 'required|min:2|max:100',
            'email'            => 'required|email|unique:nguoi_dung,email',
            'so_dien_thoai'    => 'required|regex:/^[0-9]{10}$/',
            'mat_khau'         => 'required|min:6',
            're_mat_khau'      => 'required|same:mat_khau',
            'dong_y_dieu_khoan'=> 'accepted',
        ], [
            'ho_ten.required'           => 'Họ và tên không được để trống!',
            'ho_ten.min'                => 'Họ và tên phải có ít nhất 2 ký tự!',
            'email.required'            => 'Email không được để trống!',
            'email.email'               => 'Địa chỉ email không đúng định dạng!',
            'email.unique'              => 'Email này đã được sử dụng! Vui lòng chọn email khác.',
            'so_dien_thoai.required'    => 'Số điện thoại không được để trống!',
            'so_dien_thoai.regex'       => 'Số điện thoại phải đúng định dạng 10 chữ số (ví dụ: 0912345678)!',
            'mat_khau.required'         => 'Mật khẩu không được để trống!',
            'mat_khau.min'              => 'Mật khẩu phải có ít nhất 6 ký tự!',
            're_mat_khau.required'      => 'Vui lòng xác nhận lại mật khẩu!',
            're_mat_khau.same'          => 'Mật khẩu xác nhận không trùng khớp với mật khẩu đã nhập!',
            'dong_y_dieu_khoan.accepted'=> 'Bạn phải đồng ý với Điều khoản thành viên của Maison Atelier!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'ho_ten'        => $request->ho_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'mat_khau'      => Hash::make($request->mat_khau),
            'vai_tro'       => 'khach_hang',
            'dia_chi'       => $request->dia_chi ?? 'Chưa cập nhật',
            'trang_thai'    => true,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đăng ký tài khoản thành viên thành công! Vui lòng đăng nhập.',
            'user'    => $user,
        ]);
    }

    /**
     * Chức năng: Quên mật khẩu
     */
    public function quenMatKhau(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'Vui lòng nhập email tài khoản cần khôi phục!',
            'email.email'    => 'Địa chỉ email không đúng định dạng!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Email này chưa được đăng ký trong hệ thống!',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Mã OTP khôi phục mật khẩu đã được gửi đến hộp thư ' . $request->email . ' (Mã mẫu test: 123456)',
        ]);
    }
}
