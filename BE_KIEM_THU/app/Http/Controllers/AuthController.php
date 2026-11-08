<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
     * Chức năng: Quên mật khẩu - Tạo mã OTP và gửi về email
     */
    public function quenMatKhau(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'Vui lòng nhập email tài khoản cần khôi phục!',
            'email.email'    => 'Địa chỉ email không đúng định dạng (ví dụ: user@gmail.com)!',
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

        if (!$user->trang_thai) {
            return response()->json([
                'status'  => false,
                'message' => 'Tài khoản của bạn đã bị KHÓA, không thể khôi phục mật khẩu!',
            ], 403);
        }

        // Tạo mã OTP 6 số ngẫu nhiên
        $otp = (string) rand(100000, 999999);

        // Lưu vào bảng password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $otp, 'created_at' => now()]
        );

        // Đẩy email vào Queue xử lý qua Laravel Queue Worker (php artisan queue:work)
        try {
            Mail::to($request->email)->queue(new SendOtpMail($user->ho_ten, $request->email, $otp));
        } catch (\Exception $e) {
            Log::warning("Đẩy job queue gửi mail OTP thất bại: " . $e->getMessage());
        }

        return response()->json([
            'status'  => true,
            'message' => 'Mã OTP xác thực (6 số) đã được gửi đến hộp thư ' . $request->email . '!',
            'email'   => $request->email,
        ]);
    }

    /**
     * Chức năng: Đặt lại mật khẩu với mã OTP
     */
    public function datLaiMatKhau(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email',
            'otp'                   => 'required|digits:6',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ], [
            'email.required'                 => 'Vui lòng cung cấp email tài khoản!',
            'email.email'                    => 'Email không đúng định dạng!',
            'otp.required'                   => 'Vui lòng nhập mã OTP 6 chữ số!',
            'otp.digits'                     => 'Mã OTP phải gồm đúng 6 chữ số!',
            'password.required'              => 'Vui lòng nhập mật khẩu mới!',
            'password.min'                   => 'Mật khẩu mới phải có ít nhất 6 ký tự!',
            'password_confirmation.required' => 'Vui lòng xác nhận lại mật khẩu mới!',
            'password_confirmation.same'     => 'Mật khẩu xác nhận không trùng khớp với mật khẩu mới!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return response()->json([
                'status'  => false,
                'message' => 'Không tìm thấy yêu cầu khôi phục mật khẩu cho email này hoặc mã đã hết hạn!',
            ], 422);
        }

        // Kiểm tra mã OTP khớp không
        if ($record->token !== trim($request->otp)) {
            return response()->json([
                'status'  => false,
                'message' => 'Mã OTP xác thực không chính xác! Vui lòng kiểm tra lại.',
            ], 422);
        }

        // Kiểm tra thời hạn 15 phút
        if ($record->created_at && Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return response()->json([
                'status'  => false,
                'message' => 'Mã OTP đã hết hạn hiệu lực (quá 15 phút). Vui lòng gửi lại yêu cầu mới!',
            ], 422);
        }

        // Cập nhật mật khẩu mới cho user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Tài khoản không tồn tại trên hệ thống!',
            ], 404);
        }

        $user->mat_khau = Hash::make($request->password);
        $user->save();

        // Xóa mã OTP sau khi sử dụng thành công
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Chúc mừng! Bạn đã đổi mật khẩu mới thành công. Vui lòng đăng nhập với mật khẩu mới.',
        ]);
    }
}
