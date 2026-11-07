<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SanPhamController extends BaseController
{
    public function getData()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 2;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $data = Product::with(['category', 'variants'])->get();
        return response()->json([
            'status'  => true,
            'message' => 'Lấy dữ liệu sản phẩm thành công',
            'data'    => $data,
        ]);
    }

    public function addData(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 2;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $duong_dan = $request->duong_dan ? Str::slug($request->duong_dan) : Str::slug($request->ten_san_pham);

        Product::create([
            'danh_muc_id'       => $request->danh_muc_id,
            'ten_san_pham'      => $request->ten_san_pham,
            'duong_dan'         => $duong_dan,
            'ma_san_pham'       => $request->ma_san_pham,
            'mo_ta_ngan'        => $request->mo_ta_ngan,
            'noi_dung_chi_tiet' => $request->noi_dung_chi_tiet,
            'gia_ban'           => $request->gia_ban,
            'gia_goc'           => $request->gia_goc,
            'so_luong_ton'      => $request->so_luong_ton ?? 0,
            'hinh_anh_chinh'    => $request->hinh_anh_chinh,
            'trang_thai'        => $request->trang_thai ?? 'dang_ban',
            'noi_bat'           => $request->noi_bat ?? false,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Thêm sản phẩm thành công',
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 2;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $duong_dan = $request->duong_dan ? Str::slug($request->duong_dan) : Str::slug($request->ten_san_pham);

        Product::where('id', $request->id)->update([
            'danh_muc_id'       => $request->danh_muc_id,
            'ten_san_pham'      => $request->ten_san_pham,
            'duong_dan'         => $duong_dan,
            'ma_san_pham'       => $request->ma_san_pham,
            'mo_ta_ngan'        => $request->mo_ta_ngan,
            'noi_dung_chi_tiet' => $request->noi_dung_chi_tiet,
            'gia_ban'           => $request->gia_ban,
            'gia_goc'           => $request->gia_goc,
            'so_luong_ton'      => $request->so_luong_ton ?? 0,
            'hinh_anh_chinh'    => $request->hinh_anh_chinh,
            'trang_thai'        => $request->trang_thai ?? 'dang_ban',
            'noi_bat'           => $request->noi_bat ?? false,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật sản phẩm thành công',
        ]);
    }

    public function destroy(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 2;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Product::where('id', $request->id)->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Xóa sản phẩm thành công',
        ]);
    }

    public function changeStatus(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 2;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Product::where('id', $request->id)->update(['trang_thai' => $request->trang_thai]);
        return response()->json([
            'status'  => true,
            'message' => 'Thay đổi trạng thái sản phẩm thành công'
        ]);
    }
}
