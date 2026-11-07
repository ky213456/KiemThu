<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaGiamGiaController extends BaseController
{
    public function getData()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 4;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $data = Coupon::all();
        return response()->json([
            'status'  => true,
            'message' => 'Lấy dữ liệu mã giảm giá thành công',
            'data'    => $data,
        ]);
    }

    public function addData(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 4;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Coupon::create([
            'ma_giam_gia'        => strtoupper($request->ma_giam_gia),
            'ten_ma'             => $request->ten_ma,
            'loai_giam_gia'      => $request->loai_giam_gia ?? 'tien_mat',
            'gia_tri_giam'       => $request->gia_tri_giam,
            'don_hang_toi_thieu' => $request->don_hang_toi_thieu ?? 0,
            'giam_toi_da'        => $request->giam_toi_da,
            'so_luong_ma'        => $request->so_luong_ma,
            'so_luong_da_dung'   => 0,
            'ngay_bat_dau'       => $request->ngay_bat_dau,
            'ngay_ket_thuc'      => $request->ngay_ket_thuc,
            'trang_thai'         => $request->trang_thai ?? true,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Thêm mã giảm giá thành công',
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 4;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Coupon::where('id', $request->id)->update([
            'ma_giam_gia'        => strtoupper($request->ma_giam_gia),
            'ten_ma'             => $request->ten_ma,
            'loai_giam_gia'      => $request->loai_giam_gia ?? 'tien_mat',
            'gia_tri_giam'       => $request->gia_tri_giam,
            'don_hang_toi_thieu' => $request->don_hang_toi_thieu ?? 0,
            'giam_toi_da'        => $request->giam_toi_da,
            'so_luong_ma'        => $request->so_luong_ma,
            'ngay_bat_dau'       => $request->ngay_bat_dau,
            'ngay_ket_thuc'      => $request->ngay_ket_thuc,
            'trang_thai'         => $request->has('trang_thai') ? $request->trang_thai : true,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật mã giảm giá thành công',
        ]);
    }

    public function destroy(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 4;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Coupon::where('id', $request->id)->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Xóa mã giảm giá thành công',
        ]);
    }

    public function changeStatus(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 4;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Coupon::where('id', $request->id)->update(['trang_thai' => $request->trang_thai]);
        return response()->json([
            'status'  => true,
            'message' => 'Thay đổi trạng thái mã giảm giá thành công'
        ]);
    }
}
