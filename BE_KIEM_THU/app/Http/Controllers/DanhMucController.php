<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DanhMucController extends BaseController
{
    public function getData()
    {
        $user = Auth::guard('sanctum')->user();
        // Nếu là master admin thì bỏ qua kiểm tra quyền
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 1;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $data = Category::withCount('products')->get();
        return response()->json([
            'status'  => true,
            'message' => 'Lấy dữ liệu danh mục thành công',
            'data'    => $data,
        ]);
    }

    public function addData(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 1;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $duong_dan = $request->duong_dan ? Str::slug($request->duong_dan) : Str::slug($request->ten_danh_muc);

        Category::create([
            'ten_danh_muc' => $request->ten_danh_muc,
            'duong_dan'    => $duong_dan,
            'mo_ta'        => $request->mo_ta,
            'hinh_anh'     => $request->hinh_anh,
            'trang_thai'   => $request->trang_thai ?? true,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Thêm danh mục bộ sưu tập thành công',
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 1;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $duong_dan = $request->duong_dan ? Str::slug($request->duong_dan) : Str::slug($request->ten_danh_muc);

        Category::where('id', $request->id)->update([
            'ten_danh_muc' => $request->ten_danh_muc,
            'duong_dan'    => $duong_dan,
            'mo_ta'        => $request->mo_ta,
            'hinh_anh'     => $request->hinh_anh,
            'trang_thai'   => $request->trang_thai,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật danh mục bộ sưu tập thành công',
        ]);
    }

    public function destroy(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 1;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Category::where('id', $request->id)->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Xóa danh mục thành công',
        ]);
    }

    public function changeStatus(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 1;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Category::where('id', $request->id)->update(['trang_thai' => $request->trang_thai]);
        return response()->json([
            'status'  => true,
            'message' => 'Thay đổi trạng thái danh mục thành công'
        ]);
    }
}
