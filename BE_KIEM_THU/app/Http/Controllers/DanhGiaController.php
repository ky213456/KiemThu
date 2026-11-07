<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DanhGiaController extends BaseController
{
    public function getData()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 5;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $data = Review::with(['user', 'product'])->latest()->get();
        return response()->json([
            'status'  => true,
            'message' => 'Lấy dữ liệu đánh giá sản phẩm thành công',
            'data'    => $data,
        ]);
    }

    public function addData(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 5;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Review::create([
            'nguoi_dung_id' => $request->nguoi_dung_id ?? ($user ? $user->id : null),
            'san_pham_id'   => $request->san_pham_id,
            'so_sao'        => $request->so_sao ?? 5,
            'noi_dung'      => $request->noi_dung,
            'trang_thai'    => $request->trang_thai ?? 'cho_duyet',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Gửi đánh giá sản phẩm thành công',
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 5;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Review::where('id', $request->id)->update([
            'so_sao'     => $request->so_sao,
            'noi_dung'   => $request->noi_dung,
            'trang_thai' => $request->trang_thai,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật đánh giá thành công',
        ]);
    }

    public function destroy(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 5;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Review::where('id', $request->id)->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Xóa đánh giá thành công',
        ]);
    }

    public function changeStatus(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 5;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        Review::where('id', $request->id)->update(['trang_thai' => $request->trang_thai]);
        return response()->json([
            'status'  => true,
            'message' => 'Thay đổi trạng thái đánh giá thành công'
        ]);
    }
}
