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
          <span>🔄</span> Tải Lại Dữ Liệu
        </button>
      </div>
    </div>

    <!-- BỘ LỌC TĨNH -->
    <div class="filter-bar">
      <div class="search-field">
        <span class="icon">🔍</span>
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
        </select>

        <select v-model="loc_thanh_toan" class="select-filter" data-testid="select-loc-thanh-toan">
          <option value="">Phương thức thanh toán</option>
          <option value="vnpay">VNPAY QR</option>
          <option value="chuyen_khoan">Chuyển khoản</option>
          <option value="tien_mat">Tiền mặt tại quầy</option>
        </select>

        <button type="button" class="btn-reset" data-testid="btn-dat-lai-don-hang" @click="tim_kiem = ''; loc_trang_thai = ''; loc_thanh_toan = ''">Đặt lại</button>
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
            <th width="120" class="text-center">HÀNH ĐỘNG</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in list_don_hang_filter" :key="item.id || index" :data-testid="'row-don-hang-' + (index + 1)">
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
              <span class="pay-badge pay-vnpay" :data-testid="'pay-status-' + (index + 1)">{{ item.phuong_thuc_thanh_toan ? item.phuong_thuc_thanh_toan.toUpperCase() : 'TIỀN MẶT' }}</span>
              <div class="pay-sub">{{ item.trang_thai_thanh_toan === 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</div>
            </td>
            <td>
              <span :class="['badge-status', 'status-' + item.trang_thai_don_hang]" :data-testid="'status-' + (index + 1)">
                {{ dinhDangTrangThai(item.trang_thai_don_hang) }}
              </span>
            </td>
            <td class="text-center">
              <button type="button" class="btn-action view" :data-testid="'btn-xem-' + (index + 1)" @click="moModalDon(item)">Chi tiết</button>
            </td>
          </tr>
          <tr v-if="list_don_hang_filter.length === 0">
            <td colspan="8" class="text-center py-4 text-muted">
              {{ is_loading ? 'Đang tải danh sách đơn hàng...' : 'Không tìm thấy hóa đơn nào phù hợp' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- MODAL POPUP CHI TIẾT ĐƠN HÀNG -->
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
          </div>

          <div class="bill-total-card">
            <div class="bill-line"><span>Cộng tiền hàng:</span> <span>{{ Number(selected_order.tong_tien || 0).toLocaleString('vi-VN') }}đ</span></div>
            <div class="bill-line"><span>Phí bọc hộp quà Atelier:</span> <span>Miễn phí</span></div>
            <div class="bill-line line-final">
              <strong>TỔNG THANH TOÁN:</strong>
              <strong class="total-big" data-testid="modal-tong-tien">{{ Number(selected_order.tong_tien || 0).toLocaleString('vi-VN') }}đ</strong>
            </div>
          </div>
        </div>
        <div class="modal-foot">
          <button type="button" class="btn-cancel" @click="hienModalDonHang = false">Đóng</button>
          <button type="button" class="btn-save" data-testid="btn-luu-trang-thai-don" @click="capNhatTrangThaiDon">Cập Nhật Trạng Thái</button>
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
        { id: 1, ma_don_hang: '#ORD-5821', ho_ten_nguoi_nhan: 'Chị Thảo My', so_dien_thoai_nguoi_nhan: '0912 345 678', dia_chi_giao_hang: 'Tại quầy Flagship Quận 1', phuong_thuc_thanh_toan: 'vnpay', trang_thai_thanh_toan: 'da_thanh_toan', trang_thai_don_hang: 'cho_xac_nhan', tong_tien: 2280000, ghi_chu: '2 món • Blazer Linen (M), Khăn lụa' },
        { id: 2, ma_don_hang: '#ORD-5820', ho_ten_nguoi_nhan: 'Khách vãng lai - Quầy 2', so_dien_thoai_nguoi_nhan: '0905 123 456', dia_chi_giao_hang: 'Tại quầy Flagship Quận 1', phuong_thuc_thanh_toan: 'tien_mat', trang_thai_thanh_toan: 'da_thanh_toan', trang_thai_don_hang: 'dang_xu_ly', tong_tien: 1490000, ghi_chu: '1 món • Đầm Maxi Lụa Satin (S)' },
        { id: 3, ma_don_hang: '#ORD-5819', ho_ten_nguoi_nhan: 'Instagram @helen.tran', so_dien_thoai_nguoi_nhan: '0988 776 655', dia_chi_giao_hang: 'Online Instagram Direct', phuong_thuc_thanh_toan: 'chuyen_khoan', trang_thai_thanh_toan: 'chua_thanh_toan', trang_thai_don_hang: 'dang_giao', tong_tien: 890000, ghi_chu: '1 món • Sơ mi Poplin White (M)' },
        { id: 4, ma_don_hang: '#ORD-5818', ho_ten_nguoi_nhan: 'Chị Lan Hương', so_dien_thoai_nguoi_nhan: '0933 221 100', dia_chi_giao_hang: 'Tại quầy Flagship Quận 1', phuong_thuc_thanh_toan: 'tien_mat', trang_thai_thanh_toan: 'da_thanh_toan', trang_thai_don_hang: 'da_giao', tong_tien: 3200000, ghi_chu: '3 món • Blazer & Quần Tây' }
      ],
      tim_kiem: '',
      loc_trang_thai: '',
      loc_thanh_toan: '',
      is_loading: false,
      hienModalDonHang: false,
      selected_order: {}
    };
  },
  computed: {
    list_don_hang_filter() {
      return this.list_don_hang.filter(item => {
        let khopTuKhoa = true;
        if (this.tim_kiem && this.tim_kiem.trim()) {
          const kw = this.tim_kiem.trim().toLowerCase();
          khopTuKhoa = (item.ma_don_hang && item.ma_don_hang.toLowerCase().includes(kw)) ||
                       (item.ho_ten_nguoi_nhan && item.ho_ten_nguoi_nhan.toLowerCase().includes(kw)) ||
                       (item.so_dien_thoai_nguoi_nhan && item.so_dien_thoai_nguoi_nhan.includes(kw));
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
    }
  },
  mounted() {
    this.loadData();
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

    capNhatTrangThaiDon() {
      axios
        .post('http://127.0.0.1:8000/api/admin/don-hang/change-status', {
          id: this.selected_order.id,
          trang_thai_don_hang: this.selected_order.trang_thai_don_hang
        })
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            const msg = res.data.message || 'Cập nhật tiến độ đơn hàng thành công!';
            this.$toast ? this.$toast.success(msg) : alert(msg);
            this.hienModalDonHang = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Cập nhật thất bại!';
            this.$toast ? this.$toast.error(msg) : alert(msg);
          }
        })
        .catch(err => {
          console.error('Lỗi cập nhật đơn:', err);
          const msg = err.response?.data?.message || 'Có lỗi xảy ra!';
          this.$toast ? this.$toast.error(msg) : alert(msg);
        });
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
  gap: 12px;
}
.btn-secondary-atelier {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  padding: 10px 18px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #334155;
}
.btn-secondary-atelier:hover {
  background: #f8fafc;
}

.filter-bar {
  background: #ffffff;
  padding: 14px 20px;
  border-radius: 12px;
  border: 1px solid #edf2f7;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
  margin-bottom: 20px;
}
.search-field {
  flex: 1;
  display: flex;
  align-items: center;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 0 14px;
}
.search-field .icon {
  font-size: 14px;
  color: #94a3b8;
  margin-right: 8px;
}
.input-search {
  border: none;
  background: transparent;
  padding: 10px 0;
  width: 100%;
  font-size: 14px;
  outline: none;
}
.filter-dropdowns {
  display: flex;
  gap: 10px;
}
.select-filter {
  padding: 9px 14px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  background: #ffffff;
  font-size: 13px;
  font-weight: 500;
  color: #334155;
  outline: none;
  cursor: pointer;
}
.btn-reset {
  background: transparent;
  border: none;
  color: #64748b;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  padding: 0 8px;
}

.table-card {
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #edf2f7;
  overflow: hidden;
}
.atelier-data-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}
.atelier-data-table th {
  background: #fafbfc;
  padding: 14px 18px;
  text-align: left;
  font-size: 11px;
  font-weight: 700;
  color: #64748b;
  letter-spacing: 0.8px;
  border-bottom: 1px solid #edf2f7;
}
.atelier-data-table td {
  padding: 14px 18px;
  border-bottom: 1px solid #f1f5f9;
  vertical-align: middle;
}
.code-link {
  color: #0f172a;
  font-family: monospace;
  font-size: 13px;
}
.vip-tag {
  background: #fef3c7;
  color: #b45309;
  font-size: 10px;
  padding: 2px 6px;
  border-radius: 4px;
  font-weight: 700;
  margin-left: 6px;
}
.order-items-sum {
  font-size: 13px;
  color: #334155;
}
.price-main {
  font-size: 14px;
  font-weight: 700;
  color: #0f172a;
}
.pay-badge {
  display: inline-block;
  padding: 3px 8px;
  border-radius: 4px;
  font-size: 11px;
  font-weight: 700;
}
.pay-vnpay {
  background: #eff6ff;
  color: #1d4ed8;
}
.pay-sub {
  font-size: 11px;
  color: #64748b;
  margin-top: 2px;
}
.badge-status {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}
.status-cho_xac_nhan {
  background: #fffbeb;
  color: #b45309;
}
.status-dang_xu_ly {
  background: #f0fdf4;
  color: #15803d;
}
.status-dang_giao {
  background: #eff6ff;
  color: #1d4ed8;
}
.status-da_giao {
  background: #dcfce7;
  color: #15803d;
}
.status-da_huy {
  background: #fee2e2;
  color: #b91c1c;
}

.btn-action.view {
  background: #eff6ff;
  color: #2563eb;
  padding: 6px 12px;
  border-radius: 6px;
  border: 1px solid #dbeafe;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
}

/* Modal */
.atelier-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.65);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}
.atelier-modal {
  background: #ffffff;
  border-radius: 12px;
  width: 92%;
  max-width: 600px;
  overflow: hidden;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
.modal-head {
  padding: 18px 24px;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.modal-order-tag {
  font-size: 11px;
  letter-spacing: 1.2px;
  color: #8b6b4e;
  font-weight: 700;
}
.modal-order-title {
  margin: 2px 0 0;
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
}
.btn-close {
  background: none;
  border: none;
  font-size: 22px;
  color: #94a3b8;
  cursor: pointer;
}
.modal-body {
  padding: 24px;
}
.customer-preview-box {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 20px;
}
.box-col {
  margin-bottom: 10px;
}
.box-col:last-child {
  margin-bottom: 0;
}
.box-col .label {
  display: block;
  font-size: 12px;
  color: #64748b;
  margin-bottom: 2px;
}
.select-step-input {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  color: #0f172a;
}
.bill-total-card {
  background: #fafbfc;
  border-radius: 8px;
  padding: 16px;
  border: 1px solid #e2e8f0;
}
.bill-line {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  margin-bottom: 8px;
  color: #64748b;
}
.line-final {
  margin-top: 12px;
  padding-top: 12px;
  border-top: 1px dashed #cbd5e1;
  color: #0f172a;
  font-size: 15px;
}
.total-big {
  font-size: 18px;
  color: #dc2626;
}
.modal-foot {
  padding: 16px 24px;
  background: #fafbfc;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
.btn-cancel {
  background: #ffffff;
  border: 1px solid #cbd5e1;
  padding: 9px 16px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  color: #475569;
}
.btn-save {
  background: #0f172a;
  color: #ffffff;
  border: none;
  padding: 9px 20px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}
</style>
