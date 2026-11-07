<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonHangController extends BaseController
{
    public function getData()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 3;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $data = Order::with(['items.product', 'user'])->latest()->get();
        return response()->json([
            'status'  => true,
            'message' => 'Lấy dữ liệu đơn hàng thành công',
            'data'    => $data,
        ]);
    }

    public function addData(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 3;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $ma_don_hang = $request->ma_don_hang ?? ('ORD-' . strtoupper(uniqid()));

        Order::create([
            'ma_don_hang'              => $ma_don_hang,
            'nguoi_dung_id'            => $request->nguoi_dung_id ?? ($user ? $user->id : null),
            'ho_ten_nguoi_nhan'        => $request->ho_ten_nguoi_nhan,
            'email_nguoi_nhan'         => $request->email_nguoi_nhan,
            'so_dien_thoai_nguoi_nhan' => $request->so_dien_thoai_nguoi_nhan,
            'dia_chi_giao_hang'        => $request->dia_chi_giao_hang,
            'phuong_thuc_thanh_toan'   => $request->phuong_thuc_thanh_toan ?? 'tien_mat',
            'trang_thai_thanh_toan'    => $request->trang_thai_thanh_toan ?? 'chua_thanh_toan',
            'trang_thai_don_hang'      => $request->trang_thai_don_hang ?? 'cho_xac_nhan',
            'tien_hang'                => $request->tien_hang ?? 0,
            'tien_giam_gia'            => $request->tien_giam_gia ?? 0,
            'phi_van_chuyen'           => $request->phi_van_chuyen ?? 0,
            'tong_tien'                => $request->tong_tien ?? 0,
            'ma_giam_gia'              => $request->ma_giam_gia,
            'ghi_chu'                  => $request->ghi_chu,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Tạo đơn hàng thành công',
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 3;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Order::where('id', $request->id)->update([
            'ho_ten_nguoi_nhan'        => $request->ho_ten_nguoi_nhan,
            'email_nguoi_nhan'         => $request->email_nguoi_nhan,
            'so_dien_thoai_nguoi_nhan' => $request->so_dien_thoai_nguoi_nhan,
            'dia_chi_giao_hang'        => $request->dia_chi_giao_hang,
            'phuong_thuc_thanh_toan'   => $request->phuong_thuc_thanh_toan,
            'trang_thai_thanh_toan'    => $request->trang_thai_thanh_toan,
            'trang_thai_don_hang'      => $request->trang_thai_don_hang,
            'ghi_chu'                  => $request->ghi_chu,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật đơn hàng thành công',
        ]);
    }

    public function destroy(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 3;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Order::where('id', $request->id)->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Xóa đơn hàng thành công',
        ]);
    }

    public function changeStatus(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 3;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Order::where('id', $request->id)->update(['trang_thai_don_hang' => $request->trang_thai_don_hang]);
        return response()->json([
            'status'  => true,
            'message' => 'Thay đổi trạng thái tiến độ đơn hàng thành công'
        ]);
    }

    public function changePaymentStatus(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 3;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Order::where('id', $request->id)->update(['trang_thai_thanh_toan' => $request->trang_thai_thanh_toan]);
        return response()->json([
            'status'  => true,
            'message' => 'Thay đổi trạng thái thanh toán đơn hàng thành công'
        ]);
    }
}
