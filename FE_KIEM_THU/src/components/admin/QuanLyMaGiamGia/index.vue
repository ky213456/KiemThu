<template>
  <div class="atelier-page" data-testid="page-quan-ly-ma-giam-gia">
    <!-- HEADER TRANG -->
    <div class="page-header">
      <div>
        <div class="breadcrumb-meta">KHÁCH HÀNG &amp; MARKETING &gt; VOUCHER &amp; ƯU ĐÃI</div>
        <h1 class="page-title" data-testid="title-quan-ly-ma-giam-gia">Quản Lý Voucher &amp; Đặc Quyền VIP</h1>
        <p class="page-sub">Thiết lập các mã voucher chiết khấu %, quà tặng sinh nhật và đặc quyền tri ân khách hàng Flagship</p>
      </div>

      <div class="page-actions">
        <button type="button" class="btn-secondary-atelier" @click="loadData">
          <i class="bi bi-arrow-clockwise"></i> Tải Lại Dữ Liệu
        </button>
        <button type="button" class="btn-primary-atelier" data-testid="btn-them-voucher" @click="moModalThem">
          <i class="bi bi-ticket-perforated-fill"></i> Tạo Mã Giảm Giá Mới <span class="shortcut">F2</span>
        </button>
      </div>
    </div>

    <!-- BỘ LỌC TĨNH SANG TRỌNG -->
    <div class="filter-bar">
      <div class="search-field">
        <span class="icon"><i class="bi bi-search"></i></span>
        <input
          id="tim-kiem-voucher"
          type="text"
          v-model="tim_kiem"
          placeholder="Tìm theo mã code (ATELIERVIP, WELCOME50K, SALE10...)..."
          class="input-search"
          data-testid="input-tim-kiem-voucher"
        />
      </div>

      <div class="filter-dropdowns">
        <select id="loc-loai-giam" v-model="loc_loai_giam" class="select-filter" data-testid="select-loc-loai-giam">
          <option value="">Tất cả hình thức giảm</option>
          <option value="tien_mat">Giảm tiền mặt (VNĐ)</option>
          <option value="phan_tram">Chiết khấu % giá trị</option>
        </select>

        <button type="button" class="btn-reset" @click="tim_kiem = ''; loc_loai_giam = ''"><i class="bi bi-arrow-counterclockwise"></i> Đặt lại</button>
      </div>
    </div>

    <!-- BẢNG DANH SÁCH MÃ GIẢM GIÁ PHONG CÁCH ATELIER -->
    <div class="table-card">
      <table class="atelier-data-table" data-testid="table-ma-giam-gia">
        <thead>
          <tr>
            <th width="150">MÃ CODE VOUCHER</th>
            <th>CHƯƠNG TRÌNH &amp; ĐỐI TƯỢNG</th>
            <th width="150">MỨC ƯU ĐÃI</th>
            <th width="140">ĐƠN TỐI THIỂU</th>
            <th width="130" class="text-center">ĐÃ DÙNG / GIỚI HẠN</th>
            <th width="130">TRẠNG THÁI</th>
            <th width="140" class="text-center">HÀNH ĐỘNG</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in list_voucher_filter" :key="item.id || index" :data-testid="'row-voucher-' + (index + 1)">
            <td>
              <div class="code-pill">
                <span class="code-text" :data-testid="'code-voucher-' + (index + 1)">{{ item.ma_giam_gia }}</span>
              </div>
            </td>
            <td>
              <strong class="voucher-name" :data-testid="'name-voucher-' + (index + 1)">{{ item.ten_ma }}</strong>
              <div class="voucher-sub">Hạn dùng: {{ item.ngay_ket_thuc || 'Không thời hạn' }}</div>
            </td>
            <td>
              <span :class="['discount-badge', item.loai_giam_gia === 'phan_tram' ? 'discount-percent' : 'discount-cash']" :data-testid="'value-voucher-' + (index + 1)">
                {{ item.loai_giam_gia === 'phan_tram' ? (item.gia_tri_giam + '%') : (Number(item.gia_tri_giam).toLocaleString('vi-VN') + ' đ') }}
              </span>
            </td>
            <td>
              <span class="min-order">{{ Number(item.don_hang_toi_thieu || 0).toLocaleString('vi-VN') }} đ</span>
            </td>
            <td class="text-center">
              <span class="usage-text">{{ item.so_luong_da_dung || 0 }} / {{ item.so_luong_ma || 100 }}</span>
              <div class="usage-bar-bg">
                <div class="usage-bar-fill" :style="{ width: Math.min(100, Math.round(((item.so_luong_da_dung || 0) / (item.so_luong_ma || 100)) * 100)) + '%' }"></div>
              </div>
            </td>
            <td>
              <button
                type="button"
                :class="['badge-status', (item.trang_thai == 1 || item.trang_thai == true) ? 'status-active' : 'status-inactive']"
                :data-testid="'status-voucher-' + (index + 1)"
                @click="doiTrangThai(item)"
              >
                {{ (item.trang_thai == 1 || item.trang_thai == true) ? 'Hoạt động' : 'Tạm dừng' }}
              </button>
            </td>
            <td class="text-center">
              <button type="button" class="btn-action edit" :data-testid="'btn-sua-voucher-' + (index + 1)" @click="moModalSua(item)"><i class="bi bi-pencil-square"></i> Sửa</button>
              <button type="button" class="btn-action delete" :data-testid="'btn-xoa-voucher-' + (index + 1)" @click="moModalXoa(item)"><i class="bi bi-trash3-fill"></i> Xóa</button>
            </td>
          </tr>
          <tr v-if="list_voucher_filter.length === 0">
            <td colspan="7" class="text-center py-4 text-muted">
              {{ is_loading ? 'Đang tải danh sách voucher...' : 'Không tìm thấy mã giảm giá nào phù hợp' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- MODAL POPUP THÊM / CẬP NHẬT VOUCHER -->
    <div v-if="hienModalVoucher" class="modal-backdrop" data-testid="modal-voucher" @click.self="hienModalVoucher = false">
      <div class="atelier-modal">
        <div class="modal-head">
          <div>
            <div class="modal-subtitle-tag">CẤU HÌNH KHUYẾN MÃI FLAGSHIP</div>
            <h3 class="modal-main-title" data-testid="modal-title-voucher">{{ isEdit ? 'Cập Nhật Voucher' : 'Tạo Mã Giảm Giá Mới' }}</h3>
          </div>
          <button type="button" class="btn-close-atelier" data-testid="btn-dong-modal-voucher" @click="hienModalVoucher = false">&times;</button>
        </div>

        <div class="modal-body-atelier">
          <div class="form-grid-2">
            <div class="form-item">
              <label for="v-code" class="form-label required">Mã ký hiệu Code:</label>
              <input id="v-code" type="text" v-model="active_form.ma_giam_gia" placeholder="VD: ATELIER2026" class="form-input" data-testid="input-code-voucher" />
            </div>

            <div class="form-item">
              <label for="v-type" class="form-label required">Hình thức chiết khấu:</label>
              <select id="v-type" v-model="active_form.loai_giam_gia" class="form-select" data-testid="select-type-voucher">
                <option value="tien_mat">Giảm tiền mặt (VNĐ)</option>
                <option value="phan_tram">Giảm phần trăm (%)</option>
              </select>
            </div>
          </div>

          <div class="form-item">
            <label for="v-name" class="form-label required">Tên chiến dịch ưu đãi:</label>
            <input id="v-name" type="text" v-model="active_form.ten_ma" placeholder="VD: Ưu đãi mùa lễ hội Atelier" class="form-input" data-testid="input-name-voucher" />
          </div>

          <div class="form-grid-2">
            <div class="form-item">
              <label for="v-val" class="form-label required">Mức giảm:</label>
              <input id="v-val" type="number" v-model="active_form.gia_tri_giam" class="form-input" data-testid="input-val-voucher" />
            </div>

            <div class="form-item">
              <label for="v-min" class="form-label">Đơn tối thiểu (VNĐ):</label>
              <input id="v-min" type="number" v-model="active_form.don_hang_toi_thieu" class="form-input" data-testid="input-min-voucher" />
            </div>
          </div>

          <div class="form-grid-2">
            <div class="form-item">
              <label for="v-limit" class="form-label">Số lượng phát hành:</label>
              <input id="v-limit" type="number" v-model="active_form.so_luong_ma" class="form-input" data-testid="input-limit-voucher" />
            </div>

            <div class="form-item">
              <label for="v-end" class="form-label">Ngày hết hạn:</label>
              <input id="v-end" type="date" v-model="active_form.ngay_ket_thuc" class="form-input" data-testid="input-end-voucher" />
            </div>
          </div>
        </div>

        <div class="modal-foot-atelier">
          <button type="button" class="btn-cancel" @click="hienModalVoucher = false">Hủy Bỏ</button>
          <button
            type="button"
            class="btn-save"
            data-testid="btn-luu-voucher"
            @click="isEdit ? updateVoucher() : themVoucher()"
          >
            {{ isEdit ? 'Lưu Thay Đổi' : 'Lưu Voucher' }}
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL POPUP XÓA VOUCHER -->
    <div v-if="hienModalXoa" class="modal-backdrop" @click.self="hienModalXoa = false">
      <div class="atelier-modal modal-sm">
        <div class="modal-head">
          <div>
            <div class="modal-subtitle-tag">XÁC NHẬN HÀNH ĐỘNG</div>
            <h3 class="modal-main-title">Xóa Mã Giảm Giá?</h3>
          </div>
          <button type="button" class="btn-close-atelier" @click="hienModalXoa = false">&times;</button>
        </div>
        <div class="modal-body-atelier">
          <p class="confirm-text">Mã ưu đãi <strong>{{ delete_voucher.ma_giam_gia }}</strong> này sẽ ngừng áp dụng ngay lập tức trên hệ thống POS và trang thanh toán trực tuyến.</p>
        </div>
        <div class="modal-foot-atelier">
          <button type="button" class="btn-cancel" @click="hienModalXoa = false">Hủy</button>
          <button type="button" class="btn-danger-confirm" @click="xoaVoucher">Xác Nhận Xóa</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AdminMaGiamGia',
  data() {
    return {
      list_voucher: [
        { id: 1, ma_giam_gia: 'WELCOME50K', ten_ma: 'Đặc quyền gia nhập Maison Atelier Club', loai_giam_gia: 'tien_mat', gia_tri_giam: 50000, don_hang_toi_thieu: 200000, so_luong_ma: 100, so_luong_da_dung: 5, ngay_ket_thuc: '2026-12-31', trang_thai: 1 },
        { id: 2, ma_giam_gia: 'SALE10', ten_ma: 'Chiết khấu bộ sưu tập Thu Đông', loai_giam_gia: 'phan_tram', gia_tri_giam: 10, don_hang_toi_thieu: 150000, so_luong_ma: 200, so_luong_da_dung: 12, ngay_ket_thuc: '2026-12-31', trang_thai: 1 },
        { id: 3, ma_giam_gia: 'ATELIERVIP', ten_ma: 'Tri ân VIP Diamond & Gold', loai_giam_gia: 'phan_tram', gia_tri_giam: 15, don_hang_toi_thieu: 2000000, so_luong_ma: 50, so_luong_da_dung: 34, ngay_ket_thuc: '2026-12-31', trang_thai: 1 },
        { id: 4, ma_giam_gia: 'BDAYQUEEN', ten_ma: 'Đặc quyền sinh nhật thành viên', loai_giam_gia: 'tien_mat', gia_tri_giam: 200000, don_hang_toi_thieu: 1000000, so_luong_ma: 50, so_luong_da_dung: 21, ngay_ket_thuc: '2026-12-31', trang_thai: 1 }
      ],
      tim_kiem: '',
      loc_loai_giam: '',
      is_loading: false,
      create_voucher: {
        ma_giam_gia: '',
        ten_ma: '',
        loai_giam_gia: 'tien_mat',
        gia_tri_giam: 50000,
        don_hang_toi_thieu: 200000,
        so_luong_ma: 100,
        ngay_ket_thuc: '2026-12-31',
        trang_thai: 1
      },
      edit_voucher: {
        id: '',
        ma_giam_gia: '',
        ten_ma: '',
        loai_giam_gia: 'tien_mat',
        gia_tri_giam: 0,
        don_hang_toi_thieu: 0,
        so_luong_ma: 0,
        ngay_ket_thuc: '',
        trang_thai: 1
      },
      delete_voucher: {
        id: '',
        ma_giam_gia: '',
        ten_ma: ''
      },
      hienModalVoucher: false,
      hienModalXoa: false,
      isEdit: false
    };
  },
  computed: {
    active_form() {
      return this.isEdit ? this.edit_voucher : this.create_voucher;
    },
    list_voucher_filter() {
      return this.list_voucher.filter(item => {
        let khopTuKhoa = true;
        if (this.tim_kiem && this.tim_kiem.trim()) {
          const kw = this.tim_kiem.trim().toLowerCase();
          khopTuKhoa = (item.ma_giam_gia && item.ma_giam_gia.toLowerCase().includes(kw)) ||
                       (item.ten_ma && item.ten_ma.toLowerCase().includes(kw));
        }
        let khopLoai = true;
        if (this.loc_loai_giam) {
          khopLoai = item.loai_giam_gia === this.loc_loai_giam;
        }
        return khopTuKhoa && khopLoai;
      });
    }
  },
  mounted() {
    this.loadData();
  },
  methods: {
    loadData() {
      this.is_loading = true;
      axios
        .get('http://127.0.0.1:8000/api/admin/ma-giam-gia/get-data')
        .then(res => {
          if (res.data && res.data.data && res.data.data.length > 0) {
            this.list_voucher = res.data.data;
          }
        })
        .catch(err => {
          console.error('Lỗi tải danh sách voucher:', err);
        })
        .finally(() => {
          this.is_loading = false;
        });
    },

    moModalThem() {
      this.isEdit = false;
      this.create_voucher = {
        ma_giam_gia: 'VOUCHER' + Math.floor(10 + Math.random() * 90),
        ten_ma: '',
        loai_giam_gia: 'tien_mat',
        gia_tri_giam: 50000,
        don_hang_toi_thieu: 200000,
        so_luong_ma: 100,
        ngay_ket_thuc: '2026-12-31',
        trang_thai: 1
      };
      this.hienModalVoucher = true;
    },

    themVoucher() {
      if (!this.create_voucher.ma_giam_gia || !this.create_voucher.ma_giam_gia.trim()) {
        const msg = 'Vui lòng nhập mã giảm giá!';
        this.$toast ? this.$toast.warning(msg) : alert(msg);
        return;
      }

      axios
        .post('http://127.0.0.1:8000/api/admin/ma-giam-gia/create', this.create_voucher)
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            const msg = res.data.message || 'Thêm mã giảm giá thành công!';
            this.$toast ? this.$toast.success(msg) : alert(msg);
            this.hienModalVoucher = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Thêm thất bại!';
            this.$toast ? this.$toast.error(msg) : alert(msg);
          }
        })
        .catch(err => {
          console.error('Lỗi thêm voucher:', err);
          const msg = err.response?.data?.message || 'Có lỗi xảy ra!';
          this.$toast ? this.$toast.error(msg) : alert(msg);
        });
    },

    moModalSua(item) {
      this.isEdit = true;
      this.edit_voucher = {
        id: item.id,
        ma_giam_gia: item.ma_giam_gia,
        ten_ma: item.ten_ma,
        loai_giam_gia: item.loai_giam_gia,
        gia_tri_giam: item.gia_tri_giam,
        don_hang_toi_thieu: item.don_hang_toi_thieu,
        so_luong_ma: item.so_luong_ma,
        ngay_ket_thuc: item.ngay_ket_thuc ? item.ngay_ket_thuc.split('T')[0] : '2026-12-31',
        trang_thai: item.trang_thai ? 1 : 0
      };
      this.hienModalVoucher = true;
    },

    updateVoucher() {
      axios
        .post('http://127.0.0.1:8000/api/admin/ma-giam-gia/update', this.edit_voucher)
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            const msg = res.data.message || 'Cập nhật mã giảm giá thành công!';
            this.$toast ? this.$toast.success(msg) : alert(msg);
            this.hienModalVoucher = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Cập nhật thất bại!';
            this.$toast ? this.$toast.error(msg) : alert(msg);
          }
        })
        .catch(err => {
          console.error('Lỗi cập nhật voucher:', err);
          const msg = err.response?.data?.message || 'Có lỗi xảy ra!';
          this.$toast ? this.$toast.error(msg) : alert(msg);
        });
    },

    doiTrangThai(item) {
      const trang_thai_moi = item.trang_thai ? 0 : 1;
      axios
        .post('http://127.0.0.1:8000/api/admin/ma-giam-gia/change-status', { id: item.id, trang_thai: trang_thai_moi })
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            if (this.$toast) this.$toast.success(res.data.message || 'Đổi trạng thái thành công!');
            item.trang_thai = trang_thai_moi;
          }
        })
        .catch(err => {
          console.error('Lỗi đổi trạng thái voucher:', err);
        });
    },

    moModalXoa(item) {
      this.delete_voucher = {
        id: item.id,
        ma_giam_gia: item.ma_giam_gia,
        ten_ma: item.ten_ma
      };
      this.hienModalXoa = true;
    },

    xoaVoucher() {
      axios
        .post('http://127.0.0.1:8000/api/admin/ma-giam-gia/delete', { id: this.delete_voucher.id })
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            const msg = res.data.message || 'Xóa voucher thành công!';
            this.$toast ? this.$toast.success(msg) : alert(msg);
            this.hienModalXoa = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Xóa thất bại!';
            this.$toast ? this.$toast.error(msg) : alert(msg);
          }
        })
        .catch(err => {
          console.error('Lỗi xóa voucher:', err);
          const msg = err.response?.data?.message || 'Có lỗi xảy ra!';
          this.$toast ? this.$toast.error(msg) : alert(msg);
        });
    }
  }
};
</script>

<style scoped>
.atelier-page {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  color: #0f1422;
  padding: 24px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 24px;
}
.breadcrumb-meta {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.8px;
  color: #64748b;
  margin-bottom: 6px;
}
.page-title {
  margin: 0;
  font-size: 26px;
  font-weight: 700;
  letter-spacing: -0.4px;
  color: #0f1422;
}
.page-sub {
  margin: 4px 0 0;
  font-size: 13.5px;
  color: #64748b;
}

.page-actions {
  display: flex;
  gap: 12px;
}
.btn-secondary-atelier {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
  color: #334155;
  cursor: pointer;
  transition: all 0.2s;
}
.btn-secondary-atelier:hover {
  background: #f8fafc;
  border-color: #cbd5e1;
}
.btn-primary-atelier {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  background: #0f1422;
  border: none;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
  color: #ffffff;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(15, 20, 34, 0.15);
  transition: all 0.2s;
}
.btn-primary-atelier:hover {
  background: #252e42;
}
.shortcut {
  background: rgba(255, 255, 255, 0.2);
  padding: 2px 6px;
  border-radius: 4px;
  font-size: 11px;
}

.filter-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
  background: #ffffff;
  padding: 14px 20px;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  margin-bottom: 24px;
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
  font-size: 13.5px;
  outline: none;
}
.filter-dropdowns {
  display: flex;
  gap: 12px;
}
.select-filter {
  padding: 9px 14px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  background: #ffffff;
  font-size: 13px;
  font-weight: 500;
  color: #334155;
  outline: none;
  cursor: pointer;
}
.btn-reset {
  background: none;
  border: none;
  font-size: 13px;
  font-weight: 600;
  color: #64748b;
  cursor: pointer;
  padding: 0 8px;
}

.table-card {
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
.atelier-data-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13.5px;
}
.atelier-data-table th {
  background: #f8fafc;
  padding: 14px 18px;
  text-align: left;
  font-size: 11px;
  font-weight: 700;
  color: #64748b;
  letter-spacing: 0.6px;
  border-bottom: 1px solid #e2e8f0;
}
.atelier-data-table td {
  padding: 14px 18px;
  border-bottom: 1px solid #f1f5f9;
  vertical-align: middle;
}
.code-pill {
  display: inline-block;
  background: #f1f5f9;
  border: 1px dashed #cbd5e1;
  padding: 5px 10px;
  border-radius: 6px;
}
.code-text {
  font-family: monospace;
  font-size: 13px;
  font-weight: 700;
  color: #0f1422;
}
.voucher-name {
  display: block;
  font-weight: 600;
  color: #0f1422;
}
.voucher-sub {
  font-size: 12px;
  color: #64748b;
  margin-top: 2px;
}
.discount-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 12.5px;
  font-weight: 700;
}
.discount-cash {
  background: #ecfdf5;
  color: #047857;
}
.discount-percent {
  background: #eff6ff;
  color: #1d4ed8;
}
.min-order {
  font-weight: 600;
  color: #334155;
}
.usage-text {
  font-size: 12px;
  font-weight: 600;
  color: #334155;
}
.usage-bar-bg {
  width: 100%;
  height: 5px;
  background: #e2e8f0;
  border-radius: 10px;
  overflow: hidden;
  margin-top: 4px;
}
.usage-bar-fill {
  height: 100%;
  background: #0f1422;
  border-radius: 10px;
}
.badge-status {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  border: none;
  cursor: pointer;
}
.status-active {
  background: #dcfce7;
  color: #15803d;
}
.status-inactive {
  background: #fee2e2;
  color: #b91c1c;
}
.btn-action {
  padding: 5px 10px;
  font-size: 12px;
  font-weight: 600;
  border-radius: 6px;
  border: 1px solid transparent;
  cursor: pointer;
  margin: 0 3px;
}
.btn-action.edit {
  background: #eff6ff;
  color: #2563eb;
  border-color: #dbeafe;
}
.btn-action.delete {
  background: #fef2f2;
  color: #dc2626;
  border-color: #fee2e2;
}

/* Modals */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 20, 34, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.atelier-modal {
  background: #ffffff;
  border-radius: 12px;
  width: 90%;
  max-width: 580px;
  overflow: hidden;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
}
.modal-sm {
  max-width: 420px;
}
.modal-head {
  padding: 20px 24px;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}
.modal-subtitle-tag {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.8px;
  color: #8b6b4e;
  margin-bottom: 4px;
}
.modal-main-title {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
  color: #0f1422;
}
.btn-close-atelier {
  background: none;
  border: none;
  font-size: 22px;
  color: #94a3b8;
  cursor: pointer;
}
.modal-body-atelier {
  padding: 24px;
}
.form-grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 16px;
}
.form-item {
  margin-bottom: 16px;
}
.form-label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #334155;
  margin-bottom: 6px;
}
.required::after {
  content: " *";
  color: #ef4444;
}
.form-input, .form-select {
  width: 100%;
  padding: 9px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 13.5px;
  outline: none;
  box-sizing: border-box;
}
.form-input:focus, .form-select:focus {
  border-color: #0f1422;
}
.confirm-text {
  margin: 0;
  font-size: 14px;
  color: #475569;
  line-height: 1.5;
}
.modal-foot-atelier {
  padding: 16px 24px;
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
.btn-cancel {
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  color: #475569;
  cursor: pointer;
}
.btn-save {
  padding: 8px 20px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  background: #0f1422;
  border: none;
  color: #ffffff;
  cursor: pointer;
}
.btn-danger-confirm {
  padding: 8px 20px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  background: #dc2626;
  border: none;
  color: #ffffff;
  cursor: pointer;
}
</style>
