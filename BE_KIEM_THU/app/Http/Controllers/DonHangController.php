<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonHangController extends BaseController
{
    /**
     * Lấy danh sách tất cả hóa đơn & đơn hàng POS
     * TC_ORD_01, TC_ORD_48, TC_ORD_49
     */
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

        $data = Order::with(['items.product', 'user'])->latest('id')->get();
        return response()->json([
            'status'  => true,
            'message' => 'Lấy dữ liệu đơn hàng thành công',
            'data'    => $data,
        ]);
    }

    /**
     * Tạo đơn hàng mới tại quầy POS hoặc đặt trực tuyến
     * TC_ORD_36, TC_ORD_37, TC_ORD_38, TC_ORD_39
     */
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

        // TC_ORD_38: Kiểm tra đơn hàng tổng tiền = 0
        $tong_tien = (float)($request->tong_tien ?? 0);
        if ($tong_tien <= 0) {
            return response()->json([
                'status'  => false,
                'message' => 'Tổng tiền đơn hàng phải lớn hơn 0!'
            ], 422);
        }

        // TC_ORD_37: Nếu để trống tên người nhận thì tự động gán là "Khách vãng lai"
        $ho_ten = trim($request->ho_ten_nguoi_nhan ?? '');
        if (empty($ho_ten)) {
            $ho_ten = 'Khách vãng lai';
        }

        // Kiểm tra tồn kho sản phẩm nếu chọn sản phẩm
        $sp = null;
        $qty = (int)($request->so_luong ?? 1);
        if ($request->san_pham_id) {
            $sp = Product::find($request->san_pham_id);
            if ($sp && $qty > $sp->so_luong_ton) {
                return response()->json([
                    'status'  => false,
                    'message' => "Số lượng mua ({$qty}) vượt quá số lượng tồn kho hiện có ({$sp->so_luong_ton})!"
                ], 422);
            }
        }

        // Sinh mã đơn hàng tự động chuẩn POS nếu không truyền
        $ma_don_hang = $request->ma_don_hang;
        if (empty($ma_don_hang)) {
            $ma_don_hang = '#ORD-' . rand(5822, 5999);
        }

        $order = Order::create([
            'ma_don_hang'              => $ma_don_hang,
            'nguoi_dung_id'            => $request->nguoi_dung_id ?? ($user ? $user->id : null),
            'ho_ten_nguoi_nhan'        => $ho_ten,
            'email_nguoi_nhan'         => $request->email_nguoi_nhan ?? 'pos-khachhang@atelier.vn',
            'so_dien_thoai_nguoi_nhan' => $request->so_dien_thoai_nguoi_nhan ?? '0900000000',
            'dia_chi_giao_hang'        => $request->dia_chi_giao_hang ?? 'Tại quầy Flagship Quận 1',
            'phuong_thuc_thanh_toan'   => $request->phuong_thuc_thanh_toan ?? 'tien_mat',
            'trang_thai_thanh_toan'    => $request->trang_thai_thanh_toan ?? 'da_thanh_toan',
            'trang_thai_don_hang'      => $request->trang_thai_don_hang ?? 'cho_xac_nhan',
            'tien_hang'                => $request->tien_hang ?? $tong_tien,
            'tien_giam_gia'            => $request->tien_giam_gia ?? 0,
            'phi_van_chuyen'           => $request->phi_van_chuyen ?? 0,
            'tong_tien'                => $tong_tien,
            'ma_giam_gia'              => $request->ma_giam_gia,
            'ghi_chu'                  => $request->ghi_chu ?? 'Đơn tạo trực tiếp tại quầy POS',
        ]);

        if ($sp) {
            OrderItem::create([
                'don_hang_id'        => $order->id,
                'san_pham_id'        => $sp->id,
                'ten_san_pham'       => $sp->ten_san_pham,
                'thong_tin_bien_the' => 'Tiêu chuẩn quầy POS',
                'don_gia'            => $sp->gia_ban,
                'so_luong'           => $qty,
                'thanh_tien'         => $sp->gia_ban * $qty,
            ]);

            // Trừ tồn kho tức thì khi tạo đơn bán tại quầy POS
            $sp->decrement('so_luong_ton', $qty);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Tạo đơn hàng POS thành công!',
            'data'    => $order
        ]);
    }

    /**
     * Cập nhật thông tin chi tiết hóa đơn
     */
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

        $order = Order::find($request->id);
        if (!$order) {
            return response()->json([
                'status'  => false,
                'message' => 'Không tìm thấy đơn hàng cần cập nhật!'
            ], 404);
        }

        $order->update([
            'ho_ten_nguoi_nhan'        => $request->ho_ten_nguoi_nhan ?? $order->ho_ten_nguoi_nhan,
            'email_nguoi_nhan'         => $request->email_nguoi_nhan ?? $order->email_nguoi_nhan,
            'so_dien_thoai_nguoi_nhan' => $request->so_dien_thoai_nguoi_nhan ?? $order->so_dien_thoai_nguoi_nhan,
            'dia_chi_giao_hang'        => $request->dia_chi_giao_hang ?? $order->dia_chi_giao_hang,
            'phuong_thuc_thanh_toan'   => $request->phuong_thuc_thanh_toan ?? $order->phuong_thuc_thanh_toan,
            'trang_thai_thanh_toan'    => $request->trang_thai_thanh_toan ?? $order->trang_thai_thanh_toan,
            'trang_thai_don_hang'      => $request->trang_thai_don_hang ?? $order->trang_thai_don_hang,
            'ghi_chu'                  => $request->ghi_chu ?? $order->ghi_chu,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật đơn hàng thành công!',
        ]);
    }

    /**
     * Xóa đơn hàng nháp / test
     * TC_ORD_45
     */
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

        $order = Order::with('items')->find($request->id);
        if ($order) {
            // Hoàn lại số lượng tồn kho nếu đơn bị xóa khi chưa ở trạng thái hủy
            if ($order->trang_thai_don_hang !== 'da_huy') {
                foreach ($order->items as $item) {
                    if ($item->san_pham_id) {
                        Product::where('id', $item->san_pham_id)->increment('so_luong_ton', $item->so_luong ?? 1);
                    }
                }
            }
            $order->items()->delete();
            $order->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Xóa đơn hàng thành công!',
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Đơn hàng không tồn tại!',
        ], 404);
    }

    /**
     * Cập nhật tiến độ đơn hàng & điều chỉnh tồn kho
     * TC_ORD_04 -> TC_ORD_08, TC_ORD_33, TC_ORD_40, TC_ORD_41, TC_ORD_47
     */
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

        $order = Order::with('items')->find($request->id);
        if (!$order) {
            return response()->json([
                'status'  => false,
                'message' => 'Không tìm thấy đơn hàng!'
            ], 404);
        }

        $trang_thai_cu  = $order->trang_thai_don_hang;
        $trang_thai_moi = $request->trang_thai_don_hang;

        // Cập nhật trạng thái mới
        $order->update(['trang_thai_don_hang' => $trang_thai_moi]);

        // TC_ORD_40: Trừ tồn kho sau khi đơn hoàn tất (chuyển sang 'da_giao')
        if ($trang_thai_moi === 'da_giao' && $trang_thai_cu !== 'da_giao') {
            foreach ($order->items as $item) {
                if ($item->san_pham_id) {
                    Product::where('id', $item->san_pham_id)->decrement('so_luong_ton', $item->so_luong ?? 1);
                }
                if ($item->bien_the_id) {
                    ProductVariant::where('id', $item->bien_the_id)->decrement('so_luong_ton', $item->so_luong ?? 1);
                }
            }
        }

        // TC_ORD_41: Hủy đơn hàng và hoàn lại tồn kho
        if ($trang_thai_moi === 'da_huy' && $trang_thai_cu !== 'da_huy') {
            foreach ($order->items as $item) {
                if ($item->san_pham_id) {
                    Product::where('id', $item->san_pham_id)->increment('so_luong_ton', $item->so_luong ?? 1);
                }
                if ($item->bien_the_id) {
                    ProductVariant::where('id', $item->bien_the_id)->increment('so_luong_ton', $item->so_luong ?? 1);
                }
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Thay đổi trạng thái tiến độ đơn hàng thành công!'
        ]);
    }

    /**
     * Thay đổi trạng thái thanh toán (Chưa thanh toán -> Đã thanh toán / Hoàn tiền)
     * TC_ORD_09, TC_ORD_10
     */
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

        $order = Order::find($request->id);
        if (!$order) {
            return response()->json([
                'status'  => false,
                'message' => 'Không tìm thấy hóa đơn!'
            ], 404);
        }

        $order->update(['trang_thai_thanh_toan' => $request->trang_thai_thanh_toan]);
        return response()->json([
            'status'  => true,
            'message' => 'Thay đổi trạng thái thanh toán đơn hàng thành công!'
        ]);
    }
}
