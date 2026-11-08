<template>
  <div class="atelier-page" data-testid="page-quan-ly-don-hang">
    
    <!-- HEADER TRANG -->
    <div class="page-header">
      <div>
        <div class="breadcrumb-meta">BÁN HÀNG &amp; ĐƠN HÀNG &gt; ĐƠN TẠI QUẦY &amp; ONLINE</div>
        <h1 class="page-title" data-testid="title-quan-ly-don-hang">Quản Lý Đơn Hàng &amp; Hóa Đơn POS</h1>
        <p class="page-sub">Theo dõi luồng đóng gói, vận chuyển hỏa tốc và thanh toán đa kênh Flagship</p>
      </div>

      <div class="page-actions">
        <button type="button" class="btn-secondary-atelier" @click="loadData">
          <i class="bi bi-arrow-clockwise"></i> Tải Lại Dữ Liệu
        </button>
        <button type="button" class="btn-primary-atelier" data-testid="btn-them-don-hang" @click="moModalThemDon">
          <i class="bi bi-plus-circle-fill"></i> Lập Đơn POS Mới
        </button>
      </div>
    </div>

    <!-- BỘ LỌC TĨNH -->
    <div class="filter-bar">
      <div class="search-field">
        <span class="icon"><i class="bi bi-search"></i></span>
        <input
          type="text"
          v-model="tim_kiem"
          placeholder="Tìm theo mã bill (#ORD-5821), tên khách hàng, số điện thoại..."
          class="input-search"
          data-testid="input-tim-kiem-don-hang"
        />
      </div>

      <div class="filter-dropdowns">
        <select v-model="loc_trang_thai" class="select-filter" data-testid="select-loc-trang-thai-don">
          <option value="">Tất cả tiến độ</option>
          <option value="cho_xac_nhan">Chờ xác nhận &amp; chốt size</option>
          <option value="dang_xu_ly">Đã đóng gói &amp; bọc giấy</option>
          <option value="dang_giao">Đang giao Hỏa tốc</option>
          <option value="da_giao">Hoàn tất &amp; Khách nhận</option>
          <option value="da_huy">Đã hủy đơn</option>
        </select>

        <select v-model="loc_thanh_toan" class="select-filter" data-testid="select-loc-thanh-toan">
          <option value="">Phương thức thanh toán</option>
          <option value="vnpay">VNPAY QR</option>
          <option value="chuyen_khoan">Chuyển khoản</option>
          <option value="tien_mat">Tiền mặt tại quầy</option>
        </select>

        <button type="button" class="btn-reset" data-testid="btn-dat-lai-don-hang" @click="datLaiBoLoc"><i class="bi bi-arrow-counterclockwise"></i> Đặt lại</button>
      </div>
    </div>

    <!-- BẢNG ĐƠN HÀNG ATELIER -->
    <div class="table-card">
      <table class="atelier-data-table" data-testid="table-don-hang">
        <thead>
          <tr>
            <th width="120">MÃ HÓA ĐƠN</th>
            <th>KHÁCH HÀNG &amp; KÊNH ĐẶT</th>
            <th width="120">ĐIỆN THOẠI</th>
            <th width="160">SẢN PHẨM &amp; BIẾN THỂ</th>
            <th width="130">TỔNG TIỀN</th>
            <th width="140">THANH TOÁN</th>
            <th width="150">TIẾN ĐỘ ĐƠN</th>
            <th width="160" class="text-center">HÀNH ĐỘNG</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in list_don_hang_phan_trang" :key="item.id || index" :data-testid="'row-don-hang-' + (index + 1)">
            <td><strong class="code-link" :data-testid="'code-' + (index + 1)">{{ item.ma_don_hang }}</strong></td>
            <td>
              <div>
                <strong :data-testid="'customer-name-' + (index + 1)">{{ item.ho_ten_nguoi_nhan }}</strong>
                <span class="vip-tag">Atelier VIP</span>
              </div>
              <small class="text-muted">{{ item.dia_chi_giao_hang || 'Tại quầy Flagship Quận 1' }}</small>
            </td>
            <td>{{ item.so_dien_thoai_nguoi_nhan }}</td>
            <td>
              <span class="order-items-sum">{{ item.ghi_chu || 'Trang phục thời trang cao cấp' }}</span>
            </td>
            <td><strong class="price-main" :data-testid="'total-' + (index + 1)">{{ Number(item.tong_tien || 0).toLocaleString('vi-VN') }}đ</strong></td>
            <td>
              <span :class="['pay-badge', 'pay-' + (item.phuong_thuc_thanh_toan || 'tien_mat')]" :data-testid="'pay-status-' + (index + 1)">
                {{ dinhDangPhuongThuc(item.phuong_thuc_thanh_toan) }}
              </span>
              <div class="pay-sub" :data-testid="'pay-sub-' + (index + 1)">{{ dinhDangTrangThaiThanhToan(item.trang_thai_thanh_toan) }}</div>
            </td>
            <td>
              <span :class="['badge-status', 'status-' + item.trang_thai_don_hang]" :data-testid="'status-' + (index + 1)">
                {{ dinhDangTrangThai(item.trang_thai_don_hang) }}
              </span>
            </td>
            <td class="text-center action-col">
              <button type="button" class="btn-action view" :data-testid="'btn-xem-' + (index + 1)" @click="moModalDon(item)"><i class="bi bi-eye"></i> Chi tiết</button>
              <button type="button" class="btn-action print" :data-testid="'btn-in-bill-' + (index + 1)" @click="moModalInHoaDon(item)"><i class="bi bi-printer"></i> In</button>
              <button type="button" class="btn-action delete" :data-testid="'btn-xoa-' + (index + 1)" @click="xoaDonHang(item)"><i class="bi bi-trash3-fill"></i> Xóa</button>
            </td>
          </tr>
          <tr v-if="list_don_hang_filter.length === 0">
            <td colspan="8" class="text-center py-4 text-muted">
              {{ is_loading ? 'Đang tải danh sách đơn hàng...' : 'Không tìm thấy hóa đơn nào phù hợp' }}
            </td>
          </tr>
        </tbody>
      </table>

      <!-- THANH PHÂN TRANG (PAGINATION - TC_ORD_34, TC_ORD_35) -->
      <div v-if="tongSoTrang > 1" class="pagination-bar" data-testid="pagination-bar">
        <div class="pagination-info" data-testid="pagination-info">
          Hiển thị <strong>{{ batDau }} - {{ ketThuc }}</strong> trên tổng số <strong>{{ list_don_hang_filter.length }}</strong> đơn hàng
        </div>
        <div class="pagination-controls">
          <button
            type="button"
            class="btn-page-nav"
            data-testid="btn-prev-page"
            :disabled="trang_hien_tai === 1"
            @click="trang_hien_tai--"
          >
            &laquo; Trang trước
          </button>
          
          <button
            v-for="p in tongSoTrang"
            :key="p"
            type="button"
            :class="['btn-page-num', trang_hien_tai === p ? 'active' : '']"
            :data-testid="'page-' + p"
            @click="trang_hien_tai = p"
          >
            {{ p }}
          </button>

          <button
            type="button"
            class="btn-page-nav"
            data-testid="btn-next-page"
            :disabled="trang_hien_tai === tongSoTrang"
            @click="trang_hien_tai++"
          >
            Trang sau &raquo;
          </button>
        </div>
      </div>
    </div>

    <!-- 1. MODAL CHI TIẾT & CẬP NHẬT ĐƠN HÀNG -->
    <div v-if="hienModalDonHang" class="atelier-modal-backdrop" data-testid="modal-chi-tiet-don-hang" @click.self="hienModalDonHang = false">
      <div class="atelier-modal">
        <div class="modal-head">
          <div>
            <div class="modal-order-tag">CHI TIẾT HÓA ĐƠN ATELIER</div>
            <h3 class="modal-order-title" data-testid="modal-ma-don-hang">{{ selected_order.ma_don_hang }}</h3>
          </div>
          <button type="button" class="btn-close" @click="hienModalDonHang = false">&times;</button>
        </div>
        <div class="modal-body">
          <div class="customer-preview-box">
            <div class="box-col">
              <span class="label">Khách hàng nhận:</span>
              <strong data-testid="modal-ten-khach">{{ selected_order.ho_ten_nguoi_nhan }}</strong>
            </div>
            <div class="box-col">
              <span class="label">Số điện thoại:</span>
              <strong data-testid="modal-sdt-khach">{{ selected_order.so_dien_thoai_nguoi_nhan }}</strong>
            </div>
            <div class="box-col">
              <span class="label">Cập nhật tiến độ:</span>
              <select v-model="selected_order.trang_thai_don_hang" class="atelier-input select-step-input" data-testid="select-cap-nhat-trang-thai">
                <option value="cho_xac_nhan">01. Chờ xác nhận &amp; chốt size</option>
                <option value="dang_xu_ly">02. Đã đóng gói &amp; bọc giấy nến</option>
                <option value="dang_giao">03. Đang giao Hỏa tốc (Grab/Aha)</option>
                <option value="da_giao">04. Hoàn tất &amp; Khách nhận đồ</option>
                <option value="da_huy">05. Hủy đơn hàng</option>
              </select>
            </div>
            <!-- TC_ORD_09, TC_ORD_10: Đổi trạng thái thanh toán -->
            <div class="box-col">
              <span class="label">Trạng thái thanh toán:</span>
              <select v-model="selected_order.trang_thai_thanh_toan" class="atelier-input select-step-input" data-testid="select-cap-nhat-thanh-toan">
                <option value="chua_thanh_toan">Chưa thanh toán</option>
                <option value="da_thanh_toan">Đã thanh toán thành công</option>
                <option value="hoan_tien">Hoàn tiền cho khách (Refund)</option>
              </select>
            </div>
          </div>

          <div class="bill-total-card">
            <div class="bill-line"><span>Cộng tiền hàng:</span> <span>{{ Number(selected_order.tien_hang || selected_order.tong_tien || 0).toLocaleString('vi-VN') }}đ</span></div>
            <div class="bill-line"><span>Phí bọc hộp quà Atelier:</span> <span>Miễn phí</span></div>
            <div class="bill-line line-final">
              <strong>TỔNG THANH TOÁN:</strong>
              <strong class="total-big" data-testid="modal-tong-tien">{{ Number(selected_order.tong_tien || 0).toLocaleString('vi-VN') }}đ</strong>
            </div>
          </div>
        </div>
        <div class="modal-foot">
          <button type="button" class="btn-print" data-testid="btn-in-hoa-don" @click="moModalInHoaDon(selected_order)">
            <span>🖨️</span> In Hóa Đơn
          </button>
          <div class="foot-actions-right">
            <button type="button" class="btn-cancel" @click="hienModalDonHang = false">Đóng</button>
            <button type="button" class="btn-save" data-testid="btn-luu-trang-thai-don" @click="capNhatTrangThaiDon">Lưu Cập Nhật</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. MODAL LẬP HÓA ĐƠN POS MỚI (TC_ORD_36 -> TC_ORD_39) -->
    <div v-if="hienModalThemDon" class="atelier-modal-backdrop" data-testid="modal-them-don-hang" @click.self="hienModalThemDon = false">
      <div class="atelier-modal">
        <div class="modal-head">
          <div>
            <div class="modal-order-tag">CỔNG BÁN LẺ SHOWROOM</div>
            <h3 class="modal-order-title">Lập Hóa Đơn Bán Hàng POS</h3>
          </div>
          <button type="button" class="btn-close" @click="hienModalThemDon = false">&times;</button>
        </div>
        <div class="modal-body">
          <div v-if="thongBaoLoiThem" class="modal-alert-error">
            ⚠️ {{ thongBaoLoiThem }}
          </div>

          <div class="form-grid">
            <div class="form-group span-full">
              <label class="form-label">Thu ngân phụ trách ca (Tự động):</label>
              <input
                type="text"
                :value="tenThuNgan"
                disabled
                readonly
                class="atelier-input input-readonly"
                data-testid="input-thu-ngan-pos"
              />
            </div>
            <div class="form-group">
              <label class="form-label">Tên khách hàng / Quầy đặt:</label>
              <input
                type="text"
                v-model="don_moi.ho_ten_nguoi_nhan"
                placeholder="Để trống sẽ tự gán 'Khách vãng lai'..."
                class="atelier-input"
                data-testid="input-ten-khach-moi"
              />
            </div>
            <div class="form-group">
              <label class="form-label">Số điện thoại khách:</label>
              <input
                type="text"
                v-model="don_moi.so_dien_thoai_nguoi_nhan"
                placeholder="Ví dụ: 0912 345 678..."
                class="atelier-input"
                data-testid="input-sdt-moi"
              />
            </div>
            <div class="form-group span-full">
              <label class="form-label required">Chọn sản phẩm tại quầy:</label>
              <select
                v-model="don_moi.san_pham_id"
                class="atelier-input"
                data-testid="select-san-pham-pos"
                @change="thayDoiSanPham"
              >
                <option value="">-- Chọn sản phẩm thời trang tại quầy --</option>
                <option v-for="sp in list_san_pham" :key="sp.id" :value="sp.id">
                  {{ sp.ten_san_pham }} ({{ Number(sp.gia_ban).toLocaleString('vi-VN') }}đ - Kho: {{ sp.so_luong_ton }})
                </option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label required">Số lượng mua:</label>
              <input
                type="number"
                min="1"
                v-model.number="don_moi.so_luong"
                class="atelier-input"
                data-testid="input-so-luong-pos"
                @input="tinhLaiTongTien"
              />
            </div>

            <div class="form-group">
              <label class="form-label required">Tổng giá trị đơn hàng (VNĐ):</label>
              <input
                type="number"
                v-model.number="don_moi.tong_tien"
                placeholder="Nhập tổng tiền (ví dụ: 1490000 hoặc 50000000)..."
                class="atelier-input"
                data-testid="input-tong-tien-moi"
              />
            </div>
            <div class="form-group">
              <label class="form-label">Phương thức thanh toán:</label>
              <select v-model="don_moi.phuong_thuc_thanh_toan" class="atelier-input" data-testid="select-pttt-moi">
                <option value="tien_mat">Tiền mặt tại quầy POS</option>
                <option value="vnpay">VNPAY QR Dynamic</option>
                <option value="chuyen_khoan">Chuyển khoản ngân hàng</option>
              </select>
            </div>
            <div class="form-group span-full">
              <label class="form-label">Sản phẩm &amp; Ghi chú món đồ:</label>
              <input
                type="text"
                v-model="don_moi.ghi_chu"
                placeholder="Ví dụ: 2 món • Blazer Linen (M), Khăn lụa..."
                class="atelier-input"
                data-testid="input-ghi-chu-moi"
              />
            </div>
          </div>
        </div>
        <div class="modal-foot">
          <button type="button" class="btn-cancel" @click="hienModalThemDon = false">Hủy</button>
          <button type="button" class="btn-save" data-testid="btn-luu-don-moi" @click="xuLyTaoDonHang">
            Hoàn Tất Lập Hóa Đơn POS
          </button>
        </div>
      </div>
    </div>

    <!-- 3. MODAL IN HÓA ĐƠN POS IN NHIỆT (TC_ORD_42, TC_ORD_43, TC_ORD_44) -->
    <div v-if="hienModalInBill" class="atelier-modal-backdrop" data-testid="modal-in-hoa-don" @click.self="hienModalInBill = false">
      <div class="atelier-modal modal-receipt">
        <div class="modal-head">
          <div class="modal-order-tag">HÓA ĐƠN BÁN LẺ VAT</div>
          <button type="button" class="btn-close" @click="hienModalInBill = false">&times;</button>
        </div>
        
        <!-- NỘI DUNG BILL CHUẨN IN POS -->
        <div class="receipt-paper" id="print-receipt-content">
          <div class="receipt-header">
            <h2 class="store-name">MAISON ATELIER</h2>
            <div class="store-branch" data-testid="bill-chi-nhanh">Chi nhánh: Flagship Boutique Quận 1</div>
            <div class="store-addr">Địa chỉ: 789 Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh</div>
            <div class="store-hotline">Hotline CSKH: 1900 8888 - 0988 888 888</div>
            <div class="receipt-divider">================================</div>
            <h3 class="receipt-title">PHIẾU THANH TOÁN POS</h3>
            <div class="receipt-meta">Mã hóa đơn: <strong>{{ order_to_print.ma_don_hang }}</strong></div>
            <div class="receipt-meta">Ngày lập: {{ ngayHienTai }}</div>
            <div class="receipt-meta" data-testid="bill-thu-ngan">Thu ngân: <strong>{{ tenThuNgan }}</strong></div>
          </div>

          <div class="receipt-body">
            <div class="receipt-line">
              <span>Khách hàng:</span>
              <strong>{{ order_to_print.ho_ten_nguoi_nhan }}</strong>
            </div>
            <div class="receipt-line">
              <span>Điện thoại:</span>
              <span>{{ order_to_print.so_dien_thoai_nguoi_nhan || 'N/A' }}</span>
            </div>
            <div class="receipt-line">
              <span>Địa chỉ nhận:</span>
              <span>{{ order_to_print.dia_chi_giao_hang || 'Tại quầy Flagship Quận 1' }}</span>
            </div>
            <div class="receipt-divider">--------------------------------</div>
            <div class="receipt-line">
              <span>Món đồ / Dịch vụ:</span>
              <span>{{ order_to_print.ghi_chu || 'Trang phục thời trang cao cấp' }}</span>
            </div>
            <div class="receipt-line">
              <span>Hình thức thanh toán:</span>
              <strong>{{ dinhDangPhuongThuc(order_to_print.phuong_thuc_thanh_toan) }}</strong>
            </div>
            <div class="receipt-divider">================================</div>
            <div class="receipt-line line-grand-total">
              <strong>TỔNG CỘNG:</strong>
              <strong class="receipt-price">{{ Number(order_to_print.tong_tien || 0).toLocaleString('vi-VN') }} VNĐ</strong>
            </div>
            <div class="receipt-footer">
              <p>Cảm ơn Quý khách đã lựa chọn Maison Atelier!</p>
              <small>Hóa đơn điện tử có giá trị đổi size trong vòng 07 ngày.</small>
            </div>
          </div>
        </div>

        <div class="modal-foot">
          <button type="button" class="btn-cancel" @click="hienModalInBill = false">Đóng</button>
          <button type="button" class="btn-save" data-testid="btn-thuc-hien-in" @click="thucHienInBill">
            <span>🖨️</span> Thực Hiện In Hóa Đơn
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AdminDonHang',
  data() {
    return {
      list_don_hang: [
        { id: 1, ma_don_hang: '#ORD-5821', ho_ten_nguoi_nhan: 'Chị Thảo My', so_dien_thoai_nguoi_nhan: '0912 345 678', dia_chi_giao_hang: 'Tại quầy Flagship Quận 1', phuong_thuc_thanh_toan: 'vnpay', trang_thai_thanh_toan: 'da_thanh_toan', trang_thai_don_hang: 'cho_xac_nhan', tong_tien: 2280000, ghi_chu: '2 món • Blazer Linen (M), Khăn lụa Atelier' },
        { id: 2, ma_don_hang: '#ORD-5820', ho_ten_nguoi_nhan: 'Khách vãng lai - Quầy 2', so_dien_thoai_nguoi_nhan: '0905 123 456', dia_chi_giao_hang: 'Tại quầy Flagship Quận 1', phuong_thuc_thanh_toan: 'tien_mat', trang_thai_thanh_toan: 'da_thanh_toan', trang_thai_don_hang: 'dang_xu_ly', tong_tien: 1490000, ghi_chu: '1 món • Đầm Maxi Lụa Satin (S)' },
        { id: 3, ma_don_hang: '#ORD-5819', ho_ten_nguoi_nhan: 'Instagram @helen.tran', so_dien_thoai_nguoi_nhan: '0988 776 655', dia_chi_giao_hang: 'Online Instagram Direct', phuong_thuc_thanh_toan: 'chuyen_khoan', trang_thai_thanh_toan: 'chua_thanh_toan', trang_thai_don_hang: 'dang_giao', tong_tien: 890000, ghi_chu: '1 món • Sơ mi Poplin White (M)' },
        { id: 4, ma_don_hang: '#ORD-5818', ho_ten_nguoi_nhan: 'Chị Lan Hương', so_dien_thoai_nguoi_nhan: '0933 221 100', dia_chi_giao_hang: 'Tại quầy Flagship Quận 1', phuong_thuc_thanh_toan: 'tien_mat', trang_thai_thanh_toan: 'da_thanh_toan', trang_thai_don_hang: 'da_giao', tong_tien: 3200000, ghi_chu: '3 món • Blazer & Quần Tây Premium' }
      ],
      tim_kiem: '',
      loc_trang_thai: '',
      loc_thanh_toan: '',
      is_loading: false,

      // Modal Chi tiết & Cập nhật
      hienModalDonHang: false,
      selected_order: {},

      // Modal Thêm đơn hàng POS mới
      hienModalThemDon: false,
      thongBaoLoiThem: '',
      list_san_pham: [],
      don_moi: {
        ho_ten_nguoi_nhan: '',
        so_dien_thoai_nguoi_nhan: '',
        san_pham_id: '',
        so_luong: 1,
        don_gia: 0,
        tong_tien: 0,
        phuong_thuc_thanh_toan: 'tien_mat',
        ghi_chu: ''
      },

      // Modal In Bill
      hienModalInBill: false,
      order_to_print: {},

      // Phân trang (TC_ORD_34, TC_ORD_35)
      trang_hien_tai: 1,
      so_don_moi_trang: 10
    };
  },
  computed: {
    tenThuNgan() {
      try {
        const u = JSON.parse(localStorage.getItem('auth_user') || '{}');
        return u.ho_ten || 'Thu ngân Flagship - Stylist Maison';
      } catch (e) {
        return 'Thu ngân Flagship - Stylist Maison';
      }
    },
    ngayHienTai() {
      const d = new Date();
      return d.toLocaleDateString('vi-VN') + ' ' + d.toLocaleTimeString('vi-VN');
    },

    list_don_hang_filter() {
      return this.list_don_hang.filter(item => {
        let khopTuKhoa = true;
        if (this.tim_kiem && this.tim_kiem.trim()) {
          const kw = this.tim_kiem.trim().toLowerCase();
          const maDon = (item.ma_don_hang || '').toLowerCase();
          const tenKhach = (item.ho_ten_nguoi_nhan || '').toLowerCase();
          const sdt = (item.so_dien_thoai_nguoi_nhan || '').replace(/\s+/g, '');
          const kwClean = kw.replace(/\s+/g, '').replace('#', '');

          khopTuKhoa = maDon.includes(kw) ||
                       maDon.replace('#', '').includes(kwClean) ||
                       tenKhach.includes(kw) ||
                       sdt.includes(kwClean);
        }

        let khopTrangThai = true;
        if (this.loc_trang_thai) {
          khopTrangThai = item.trang_thai_don_hang === this.loc_trang_thai;
        }

        let khopThanhToan = true;
        if (this.loc_thanh_toan) {
          khopThanhToan = item.phuong_thuc_thanh_toan === this.loc_thanh_toan;
        }

        return khopTuKhoa && khopTrangThai && khopThanhToan;
      });
    },

    tongSoTrang() {
      return Math.ceil(this.list_don_hang_filter.length / this.so_don_moi_trang) || 1;
    },

    batDau() {
      if (this.list_don_hang_filter.length === 0) return 0;
      return (this.trang_hien_tai - 1) * this.so_don_moi_trang + 1;
    },

    ketThuc() {
      const end = this.trang_hien_tai * this.so_don_moi_trang;
      return end > this.list_don_hang_filter.length ? this.list_don_hang_filter.length : end;
    },

    list_don_hang_phan_trang() {
      const start = (this.trang_hien_tai - 1) * this.so_don_moi_trang;
      return this.list_don_hang_filter.slice(start, start + this.so_don_moi_trang);
    }
  },
  watch: {
    tim_kiem() {
      this.trang_hien_tai = 1;
    },
    loc_trang_thai() {
      this.trang_hien_tai = 1;
    },
    loc_thanh_toan() {
      this.trang_hien_tai = 1;
    }
  },
  mounted() {
    this.loadData();
    this.loadSanPham();
  },
  methods: {
    dinhDangTrangThai(status) {
      const map = {
        'cho_xac_nhan': 'Chờ xác nhận',
        'dang_xu_ly': 'Đã đóng gói',
        'dang_giao': 'Đang giao hàng',
        'da_giao': 'Hoàn tất & Khách nhận',
        'da_huy': 'Đã hủy'
      };
      return map[status] || status;
    },

    dinhDangPhuongThuc(pt) {
      const map = {
        'vnpay': 'VNPAY QR',
        'chuyen_khoan': 'CHUYỂN KHOẢN',
        'tien_mat': 'TIỀN MẶT'
      };
      return map[pt] || (pt ? pt.toUpperCase() : 'TIỀN MẶT');
    },

    dinhDangTrangThaiThanhToan(tt) {
      const map = {
        'da_thanh_toan': 'Đã thanh toán',
        'chua_thanh_toan': 'Chưa thanh toán',
        'hoan_tien': 'Đã hoàn tiền (Refund)'
      };
      return map[tt] || tt;
    },

    datLaiBoLoc() {
      this.tim_kiem = '';
      this.loc_trang_thai = '';
      this.loc_thanh_toan = '';
      this.trang_hien_tai = 1;
    },

    loadData() {
      this.is_loading = true;
      axios
        .get('http://127.0.0.1:8000/api/admin/don-hang/get-data')
        .then(res => {
          if (res.data && res.data.data && res.data.data.length > 0) {
            this.list_don_hang = res.data.data;
          }
        })
        .catch(err => {
          console.error('Lỗi tải danh sách đơn hàng:', err);
        })
        .finally(() => {
          this.is_loading = false;
        });
    },

    moModalDon(item) {
      this.selected_order = { ...item };
      this.hienModalDonHang = true;
    },

    loadSanPham() {
      axios
        .get('http://127.0.0.1:8000/api/admin/san-pham/get-data')
        .then(res => {
          if (res.data && res.data.data && res.data.data.length > 0) {
            this.list_san_pham = res.data.data;
          }
        })
        .catch(err => {
          console.error('Lỗi tải sản phẩm:', err);
        });
    },

    moModalThemDon() {
      this.thongBaoLoiThem = '';
      const firstSp = this.list_san_pham && this.list_san_pham.length > 0 ? this.list_san_pham[0] : null;
      this.don_moi = {
        ho_ten_nguoi_nhan: '',
        so_dien_thoai_nguoi_nhan: '',
        san_pham_id: firstSp ? firstSp.id : '',
        so_luong: 1,
        don_gia: firstSp ? Number(firstSp.gia_ban || 0) : 199000,
        tong_tien: firstSp ? Number(firstSp.gia_ban || 0) : 199000,
        phuong_thuc_thanh_toan: 'tien_mat',
        ghi_chu: firstSp ? `1 món • ${firstSp.ten_san_pham}` : '1 món • Áo Thun Nam Cotton Compact'
      };
      this.hienModalThemDon = true;
    },

    thayDoiSanPham() {
      const sp = this.list_san_pham.find(item => item.id == this.don_moi.san_pham_id);
      if (sp) {
        this.don_moi.don_gia = Number(sp.gia_ban || 0);
        this.don_moi.tong_tien = this.don_moi.don_gia * (Number(this.don_moi.so_luong) || 1);
        this.don_moi.ghi_chu = `${this.don_moi.so_luong || 1} món • ${sp.ten_san_pham}`;
      }
    },

    tinhLaiTongTien() {
      const sp = this.list_san_pham.find(item => item.id == this.don_moi.san_pham_id);
      if (sp) {
        this.don_moi.tong_tien = Number(sp.gia_ban || 0) * (Number(this.don_moi.so_luong) || 1);
        this.don_moi.ghi_chu = `${this.don_moi.so_luong || 1} món • ${sp.ten_san_pham}`;
      }
    },

    xuLyTaoDonHang() {
      this.thongBaoLoiThem = '';

      // Kiểm tra số lượng tồn kho
      const sp = this.list_san_pham.find(item => item.id == this.don_moi.san_pham_id);
      if (sp && Number(this.don_moi.so_luong) > Number(sp.so_luong_ton)) {
        this.thongBaoLoiThem = `Số lượng mua (${this.don_moi.so_luong}) vượt quá số lượng tồn kho hiện có (${sp.so_luong_ton})!`;
        return;
      }

      // TC_ORD_38: Kiểm tra đơn hàng tổng tiền = 0
      if (!this.don_moi.tong_tien || Number(this.don_moi.tong_tien) <= 0) {
        this.thongBaoLoiThem = 'Tổng tiền đơn hàng phải lớn hơn 0!';
        return;
      }

      // TC_ORD_37: Nếu để trống tên người nhận thì tự động gán là "Khách vãng lai"
      if (!this.don_moi.ho_ten_nguoi_nhan || !this.don_moi.ho_ten_nguoi_nhan.trim()) {
        this.don_moi.ho_ten_nguoi_nhan = 'Khách vãng lai';
      }

      axios
        .post('http://127.0.0.1:8000/api/admin/don-hang/create', this.don_moi)
        .then(res => {
          if (res.data.status === true || res.data.status == 1) {
            this.$toast ? this.$toast.success(res.data.message || 'Tạo đơn hàng POS thành công!') : alert(res.data.message || 'Tạo đơn hàng POS thành công!');
            this.hienModalThemDon = false;
            this.loadData();
            this.loadSanPham();
          } else {
            this.thongBaoLoiThem = res.data.message || 'Tạo đơn hàng thất bại!';
          }
        })
        .catch(err => {
          this.thongBaoLoiThem = err.response?.data?.message || 'Có lỗi xảy ra khi tạo đơn!';
        });
    },

    capNhatTrangThaiDon() {
      const p1 = axios.post('http://127.0.0.1:8000/api/admin/don-hang/change-status', {
        id: this.selected_order.id,
        trang_thai_don_hang: this.selected_order.trang_thai_don_hang
      });

      const p2 = axios.post('http://127.0.0.1:8000/api/admin/don-hang/change-payment-status', {
        id: this.selected_order.id,
        trang_thai_thanh_toan: this.selected_order.trang_thai_thanh_toan
      });

      Promise.all([p1, p2])
        .then(() => {
          this.$toast ? this.$toast.success('Cập nhật tiến độ & thanh toán đơn hàng thành công!') : alert('Cập nhật tiến độ & thanh toán đơn hàng thành công!');
          this.hienModalDonHang = false;
          this.loadData();
          this.loadSanPham();
        })
        .catch(err => {
          console.error('Lỗi cập nhật đơn:', err);
          const msgErr = err.response?.data?.message || 'Có lỗi khi cập nhật!'; this.$toast ? this.$toast.error(msgErr) : alert(msgErr);
        });
    },

    xoaDonHang(item) {
      if (confirm(`Bạn có chắc chắn muốn xóa đơn hàng ${item.ma_don_hang} không?`)) {
        axios
          .post('http://127.0.0.1:8000/api/admin/don-hang/delete', { id: item.id })
          .then(res => {
            if (res.data.status === true || res.data.status == 1) {
              this.$toast ? this.$toast.success('Xóa đơn hàng thành công!') : alert('Xóa đơn hàng thành công!');
              this.loadData();
              this.loadSanPham();
            } else {
              const msgDelFail = res.data.message || 'Xóa đơn hàng thất bại!'; this.$toast ? this.$toast.error(msgDelFail) : alert(msgDelFail);
            }
          })
          .catch(err => {
            const msgDelErr = err.response?.data?.message || 'Có lỗi khi xóa đơn hàng!'; this.$toast ? this.$toast.error(msgDelErr) : alert(msgDelErr);
          });
      }
    },

    moModalInHoaDon(item) {
      this.order_to_print = { ...item };
      this.hienModalInBill = true;
    },

    thucHienInBill() {
      window.print();
    }
  }
};
</script>

<style scoped>
.atelier-page {
  padding: 24px 30px;
  background-color: #f6f7f9;
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  color: #1e293b;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 12px;
}
.breadcrumb-meta {
  font-size: 11px;
  letter-spacing: 1.5px;
  color: #8b6b4e;
  font-weight: 700;
  text-transform: uppercase;
  margin-bottom: 4px;
}
.page-title {
  margin: 0;
  font-size: 26px;
  font-weight: 700;
  color: #0f172a;
}
.page-sub {
  margin: 4px 0 0;
  font-size: 14px;
  color: #64748b;
}
.page-actions {
  display: flex;
  gap: 10px;
}
.btn-primary-atelier {
  background: #0f172a;
  color: #fff;
  border: none;
  padding: 10px 18px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.btn-primary-atelier:hover {
  background: #1e293b;
}
.btn-secondary-atelier {
  background: #fff;
  color: #334155;
  border: 1px solid #cbd5e1;
  padding: 10px 16px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.btn-secondary-atelier:hover {
  background: #f1f5f9;
}

.filter-bar {
  display: flex;
  justify-content: space-between;
  background: #fff;
  padding: 16px 20px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  margin-bottom: 20px;
  gap: 16px;
  flex-wrap: wrap;
}
.search-field {
  position: relative;
  flex: 1;
  min-width: 280px;
}
.search-field .icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
}
.input-search {
  width: 100%;
  padding: 9px 12px 9px 36px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 14px;
  outline: none;
}
.input-search:focus {
  border-color: #8b6b4e;
}
.filter-dropdowns {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}
.select-filter {
  padding: 9px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  background: #fff;
  font-size: 13px;
  color: #334155;
  cursor: pointer;
}
.btn-reset {
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  padding: 9px 14px;
  border-radius: 6px;
  font-size: 13px;
  color: #64748b;
  cursor: pointer;
}
.btn-reset:hover {
  background: #e2e8f0;
}

.table-card {
  background: #fff;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  overflow: hidden;
}
.atelier-data-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}
.atelier-data-table th {
  background: #f8fafc;
  padding: 12px 16px;
  text-align: left;
  font-weight: 700;
  color: #475569;
  border-bottom: 1px solid #e2e8f0;
  font-size: 11px;
  letter-spacing: 0.5px;
}
.atelier-data-table td {
  padding: 14px 16px;
  border-bottom: 1px solid #f1f5f9;
  vertical-align: middle;
}
.code-link {
  color: #8b6b4e;
  font-weight: 700;
}
.vip-tag {
  display: inline-block;
  background: #fef3c7;
  color: #92400e;
  font-size: 10px;
  font-weight: 700;
  padding: 2px 6px;
  border-radius: 4px;
  margin-left: 6px;
}
.price-main {
  color: #0f172a;
  font-size: 14px;
  font-weight: 700;
}
.pay-badge {
  display: inline-block;
  font-size: 11px;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 4px;
}
.pay-vnpay { background: #fee2e2; color: #dc2626; }
.pay-chuyen_khoan { background: #e0e7ff; color: #4338ca; }
.pay-tien_mat { background: #dcfce7; color: #15803d; }
.pay-sub {
  font-size: 11px;
  color: #64748b;
  margin-top: 2px;
}

.badge-status {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
}
.status-cho_xac_nhan { background: #fef9c3; color: #854d0e; }
.status-dang_xu_ly { background: #e0f2fe; color: #0369a1; }
.status-dang_giao { background: #ede9fe; color: #6d28d9; }
.status-da_giao { background: #dcfce7; color: #15803d; }
.status-da_huy { background: #fee2e2; color: #991b1b; }

.action-col {
  white-space: nowrap;
}
.btn-action {
  border: none;
  padding: 6px 10px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  margin: 0 2px;
}
.btn-action.view { background: #f1f5f9; color: #334155; }
.btn-action.view:hover { background: #e2e8f0; }
.btn-action.print { background: #e0e7ff; color: #3730a3; }
.btn-action.print:hover { background: #c7d2fe; }
.btn-action.delete { background: #fee2e2; color: #991b1b; }
.btn-action.delete:hover { background: #fecaca; }

/* PHÂN TRANG (PAGINATION) */
.pagination-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 20px;
  border-top: 1px solid #e2e8f0;
  background: #f8fafc;
  flex-wrap: wrap;
  gap: 10px;
}
.pagination-info {
  font-size: 13px;
  color: #64748b;
}
.pagination-controls {
  display: flex;
  gap: 6px;
}
.btn-page-nav {
  padding: 6px 12px;
  border: 1px solid #cbd5e1;
  background: #fff;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  color: #475569;
  cursor: pointer;
}
.btn-page-nav:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
.btn-page-num {
  padding: 6px 12px;
  border: 1px solid #cbd5e1;
  background: #fff;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  color: #475569;
  cursor: pointer;
}
.btn-page-num.active {
  background: #0f172a;
  color: #fff;
  border-color: #0f172a;
}

/* MODAL CHUNG */
.atelier-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  padding: 16px;
}
.atelier-modal {
  background: #fff;
  border-radius: 12px;
  width: 100%;
  max-width: 560px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
  overflow: hidden;
}
.modal-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 18px 24px;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}
.modal-order-tag {
  font-size: 10px;
  font-weight: 700;
  color: #8b6b4e;
  letter-spacing: 1px;
}
.modal-order-title {
  margin: 2px 0 0;
  font-size: 18px;
  color: #0f172a;
}
.btn-close {
  background: none;
  border: none;
  font-size: 24px;
  color: #94a3b8;
  cursor: pointer;
}
.modal-body {
  padding: 20px 24px;
}
.modal-foot {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 24px;
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
}
.foot-actions-right {
  display: flex;
  gap: 10px;
}

.customer-preview-box {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 16px;
}
.box-col {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.box-col .label {
  font-size: 12px;
  color: #64748b;
  font-weight: 600;
}
.select-step-input {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 13px;
  color: #0f172a;
  font-weight: 500;
}
.bill-total-card {
  background: #f1f5f9;
  padding: 14px;
  border-radius: 8px;
  font-size: 13px;
}
.bill-line {
  display: flex;
  justify-content: space-between;
  margin-bottom: 6px;
}
.line-final {
  border-top: 1px dashed #cbd5e1;
  padding-top: 8px;
  margin-top: 8px;
  margin-bottom: 0;
}
.total-big {
  color: #b91c1c;
  font-size: 16px;
}
.btn-cancel {
  background: #fff;
  border: 1px solid #cbd5e1;
  padding: 8px 16px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  color: #475569;
}
.btn-save {
  background: #0f172a;
  color: #fff;
  border: none;
  padding: 8px 18px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
}
.btn-print {
  background: #e0e7ff;
  color: #3730a3;
  border: 1px solid #c7d2fe;
  padding: 8px 14px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

/* FORM GRID CHO MODAL TẠO ĐƠN */
.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}
.form-group {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.form-group.span-full {
  grid-column: span 2;
}
.form-label {
  font-size: 12px;
  font-weight: 600;
  color: #475569;
}
.form-label.required::after {
  content: " *";
  color: #dc2626;
}
.atelier-input {
  padding: 8px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 13px;
}
.input-readonly {
  background-color: #f1f5f9;
  color: #475569;
  cursor: not-allowed;
  font-weight: 600;
}
.modal-alert-error {
  background: #fee2e2;
  color: #b91c1c;
  padding: 10px 14px;
  border-radius: 6px;
  font-size: 13px;
  margin-bottom: 14px;
  font-weight: 600;
}

/* MODAL IN BILL */
.modal-receipt {
  max-width: 440px;
}
.receipt-paper {
  background: #fffbf2;
  border: 1px dashed #d1d5db;
  padding: 20px;
  font-family: "Courier New", Courier, monospace;
  color: #111;
  font-size: 12px;
}
.receipt-header {
  text-align: center;
}
.store-name {
  margin: 0 0 4px;
  font-size: 18px;
  letter-spacing: 2px;
}
.store-branch {
  font-weight: 700;
  margin-bottom: 2px;
}
.receipt-divider {
  margin: 8px 0;
  letter-spacing: 1px;
}
.receipt-title {
  margin: 6px 0;
  font-size: 14px;
}
.receipt-meta {
  font-size: 11px;
  margin-bottom: 2px;
}
.receipt-body {
  margin-top: 10px;
}
.receipt-line {
  display: flex;
  justify-content: space-between;
  margin-bottom: 4px;
}
.line-grand-total {
  font-size: 14px;
  margin-top: 8px;
}
.receipt-price {
  font-size: 14px;
}
.receipt-footer {
  text-align: center;
  margin-top: 14px;
  font-size: 11px;
}
</style>
