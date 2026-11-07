<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class NguoiDungController extends BaseController
{
    public function getData()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 6;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $data = User::all();
        return response()->json([
            'status'  => true,
            'message' => 'Lấy dữ liệu người dùng thành công',
            'data'    => $data,
        ]);
    }

    public function addData(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 6;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        User::create([
            'ho_ten'        => $request->ho_ten,
            'email'         => $request->email,
            'mat_khau'      => Hash::make($request->mat_khau ?? '123456'),
            'vai_tro'       => $request->vai_tro ?? 'khach_hang',
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi'       => $request->dia_chi,
            'anh_dai_dien'  => $request->anh_dai_dien,
            'trang_thai'    => $request->trang_thai ?? true,
            'is_master'     => $request->is_master ?? 0,
            'id_chuc_vu'    => $request->id_chuc_vu ?? 1,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Thêm tài khoản người dùng thành công',
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 6;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        $updateData = [
            'ho_ten'        => $request->ho_ten,
            'email'         => $request->email,
            'vai_tro'       => $request->vai_tro ?? 'khach_hang',
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi'       => $request->dia_chi,
            'anh_dai_dien'  => $request->anh_dai_dien,
            'trang_thai'    => $request->has('trang_thai') ? $request->trang_thai : true,
            'id_chuc_vu'    => $request->id_chuc_vu,
        ];

        if ($request->filled('mat_khau')) {
            $updateData['mat_khau'] = Hash::make($request->mat_khau);
        }

        User::where('id', $request->id)->update($updateData);

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật tài khoản người dùng thành công',
        ]);
    }

    public function destroy(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 6;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        User::where('id', $request->id)->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Xóa tài khoản người dùng thành công',
        ]);
    }

    public function changeStatus(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->is_master != 1) {
            $id_chuc_nang = 6;
            $id_chuc_vu   = $user->id_chuc_vu;
            $check        = PhanQuyen::where('id_chuc_vu', $id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
            if (!$check) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Bạn không có quyền thực hiện chức năng này!'
                ]);
            }
        }

        User::where('id', $request->id)->update(['trang_thai' => $request->trang_thai]);
        return response()->json([
            'status'  => true,
            'message' => 'Thay đổi trạng thái tài khoản thành công'
        ]);
    }
}
