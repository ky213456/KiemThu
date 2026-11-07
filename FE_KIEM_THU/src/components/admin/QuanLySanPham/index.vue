<template>
  <div class="atelier-page" data-testid="page-quan-ly-san-pham">
    
    <!-- HEADER TRANG -->
    <div class="page-header">
      <div>
        <div class="breadcrumb-meta">QUẢN LÝ SẢN PHẨM &amp; KHO &gt; TẤT CẢ SẢN PHẨM</div>
        <h1 class="page-title" data-testid="title-quan-ly-san-pham">Danh Sách Sản Phẩm Thời Trang</h1>
        <p class="page-sub">Quản lý các bộ sưu tập áo quần, biến thể kích cỡ S/M/L và số lượng tồn kho Flagship</p>
      </div>

      <div class="page-actions">
        <button type="button" class="btn-secondary-atelier" @click="loadData">
          <span>🔄</span> Tải Lại Dữ Liệu
        </button>
        <button type="button" class="btn-primary-atelier" data-testid="btn-them-san-pham" @click="moModalThem">
          <span>+</span> Thêm Mẫu Mới <span class="shortcut">F2</span>
        </button>
      </div>
    </div>

    <!-- BỘ LỌC TĨNH SANG TRỌNG -->
    <div class="filter-bar">
      <div class="search-field">
        <span class="icon">🔍</span>
        <input
          type="text"
          v-model="tim_kiem"
          placeholder="Tìm theo tên sản phẩm, mã SKU (WA-B224...), chất liệu vải..."
          class="input-search"
          data-testid="input-tim-kiem"
        />
      </div>

      <div class="filter-dropdowns">
        <select v-model="loc_danh_muc" class="select-filter" data-testid="select-loc-danh-muc">
          <option value="">Tất cả Bộ sưu tập</option>
          <option value="1">Blazer &amp; Vest Nữ</option>
          <option value="2">Đầm Dạ Tiệc &amp; Maxi</option>
          <option value="3">Sơ Mi &amp; Poplin</option>
          <option value="4">Quần Tây &amp; Chân Váy</option>
          <option value="5">Áo Khoác Thu Đông</option>
        </select>

        <select v-model="loc_trang_thai" class="select-filter" data-testid="select-loc-trang-thai">
          <option value="">Trạng thái kho</option>
          <option value="dang_ban">Đang kinh doanh</option>
          <option value="het_hang">Cảnh báo hết size</option>
          <option value="tam_an">Tạm ẩn lookbook</option>
        </select>

        <button type="button" class="btn-reset" data-testid="btn-dat-lai-bo-loc" @click="datLaiBoLoc">Đặt lại</button>
      </div>
    </div>

    <!-- BẢNG SẢN PHẨM PHONG CÁCH ATELIER -->
    <div class="table-card">
      <table class="atelier-data-table" data-testid="table-san-pham">
        <thead>
          <tr>
            <th width="50" class="text-center">STT</th>
            <th>SẢN PHẨM &amp; MÃ SKU</th>
            <th width="150">BỘ SƯU TẬP</th>
            <th width="130">GIÁ NIÊM YẾT</th>
            <th width="90" class="text-center">ĐÃ BÁN</th>
            <th width="160" class="text-center">TỒN KHO SIZE (S / M / L)</th>
            <th width="130">TRẠNG THÁI</th>
            <th width="120" class="text-center">HÀNH ĐỘNG</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in list_san_pham_filter" :key="item.id || index" :data-testid="'row-san-pham-' + (index + 1)">
            <td class="text-center text-muted">{{ String(index + 1).padStart(2, '0') }}</td>
            <td>
              <div class="prod-cell">
                <img :src="item.hinh_anh_chinh || 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=120&q=80'" :alt="item.ten_san_pham" class="prod-thumb" />
                <div>
                  <strong class="prod-name" :data-testid="'name-' + (index + 1)">{{ item.ten_san_pham }}</strong>
                  <span class="prod-sku-tag" :data-testid="'sku-' + (index + 1)">SKU: {{ item.ma_san_pham }} • {{ item.mo_ta_ngan || 'Thiết kế cao cấp' }}</span>
                </div>
              </div>
            </td>
            <td><span class="cat-pill">{{ item.category ? item.category.ten_danh_muc : 'Thời trang Maison' }}</span></td>
            <td>
              <strong class="price-main" :data-testid="'price-' + (index + 1)">{{ dinhDangTien(item.gia_ban) }}</strong>
              <div v-if="item.gia_goc" class="price-orig">{{ dinhDangTien(item.gia_goc) }}</div>
            </td>
            <td class="text-center font-bold">{{ item.da_ban || 28 }}</td>
            <td class="text-center">
              <div class="size-group">
                <span :class="['size-tag', (item.so_luong_ton > 5) ? 'tag-ok' : (item.so_luong_ton > 0 ? 'tag-low' : 'tag-out')]">
                  Kho: {{ item.so_luong_ton }}
                </span>
              </div>
            </td>
            <td>
              <button
                type="button"
                :class="['badge-status', item.trang_thai === 'dang_ban' ? 'status-active' : (item.trang_thai === 'het_hang' ? 'status-out' : 'status-pending')]"
                :data-testid="'status-' + (index + 1)"
                @click="doiTrangThai(item)"
                :title="'Click để đổi trạng thái'"
              >
                {{ item.trang_thai === 'dang_ban' ? 'Đang bán' : (item.trang_thai === 'het_hang' ? 'Hết hàng' : 'Tạm ẩn') }}
              </button>
            </td>
            <td class="text-center">
              <button type="button" class="btn-action edit" :data-testid="'btn-sua-' + (index + 1)" @click="moModalSua(item)">Sửa</button>
              <button type="button" class="btn-action delete" :data-testid="'btn-xoa-' + (index + 1)" @click="moModalXoa(item)">Xóa</button>
            </td>
          </tr>
          <tr v-if="list_san_pham_filter.length === 0">
            <td colspan="8" class="text-center py-4 text-muted">
              {{ is_loading ? 'Đang tải danh sách sản phẩm...' : 'Không tìm thấy sản phẩm thời trang nào phù hợp' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- MODAL THÊM / CẬP NHẬT SẢN PHẨM -->
    <div v-if="hienModalSanPham" class="atelier-modal-backdrop" data-testid="modal-san-pham" @click.self="hienModalSanPham = false">
      <div class="atelier-modal">
        <div class="modal-head">
          <h3>{{ isEdit ? 'Chỉnh Sửa Mẫu Sản Phẩm' : 'Thêm Mới Thiết Kế Thời Trang' }}</h3>
          <button type="button" class="btn-close" @click="hienModalSanPham = false">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-col">
              <label class="required">Mã sản phẩm (SKU):</label>
              <input type="text" v-model="active_form.ma_san_pham" placeholder="WA-B224-SND..." class="atelier-input" data-testid="modal-input-ma-sp" />
            </div>
            <div class="form-col">
              <label class="required">Bộ sưu tập:</label>
              <select v-model="active_form.danh_muc_id" class="atelier-input" data-testid="modal-select-danh-muc">
                <option value="1">Blazer &amp; Vest Nữ</option>
                <option value="2">Đầm Dạ Tiệc &amp; Maxi</option>
                <option value="3">Sơ Mi &amp; Poplin</option>
                <option value="4">Quần Tây &amp; Chân Váy</option>
                <option value="5">Áo Khoác Thu Đông</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="required">Tên sản phẩm thời trang:</label>
            <input type="text" v-model="active_form.ten_san_pham" placeholder="Nhập tên áo quần..." class="atelier-input" data-testid="modal-input-ten-sp" />
          </div>

          <div class="form-row">
            <div class="form-col">
              <label class="required">Giá bán (VNĐ):</label>
              <input type="number" v-model="active_form.gia_ban" class="atelier-input" data-testid="modal-input-gia-ban" />
            </div>
            <div class="form-col">
              <label>Giá gốc niêm yết:</label>
              <input type="number" v-model="active_form.gia_goc" class="atelier-input" data-testid="modal-input-gia-goc" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-col">
              <label>Số lượng tồn kho:</label>
              <input type="number" v-model="active_form.so_luong_ton" class="atelier-input" />
            </div>
            <div class="form-col">
              <label>Trạng thái kinh doanh:</label>
              <select v-model="active_form.trang_thai" class="atelier-input">
                <option value="dang_ban">Đang bán</option>
                <option value="het_hang">Hết hàng</option>
                <option value="tam_an">Tạm ẩn</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Đường dẫn hình ảnh lookbook:</label>
            <input type="text" v-model="active_form.hinh_anh_chinh" class="atelier-input" data-testid="modal-input-hinh-anh" />
          </div>

          <div class="form-group">
            <label>Mô tả ngắn / Chất liệu:</label>
            <input type="text" v-model="active_form.mo_ta_ngan" placeholder="Be cát tự nhiên, Linen cao cấp..." class="atelier-input" />
          </div>
        </div>
        <div class="modal-foot">
          <button type="button" class="btn-cancel" @click="hienModalSanPham = false">Hủy Bỏ</button>
          <button
            type="button"
            class="btn-save"
            data-testid="btn-luu-san-pham"
            @click="isEdit ? updateSanPham() : themSanPham()"
          >
            {{ isEdit ? 'Lưu Thay Đổi' : 'Lưu Thông Tin' }}
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL XÁC NHẬN XÓA -->
    <div v-if="hienModalXoa" class="atelier-modal-backdrop" @click.self="hienModalXoa = false">
      <div class="atelier-modal modal-small">
        <div class="modal-head">
          <h3>Xác Nhận Xóa Mẫu</h3>
          <button type="button" class="btn-close" @click="hienModalXoa = false">&times;</button>
        </div>
        <div class="modal-body">
          <p>Bạn có chắc muốn xóa mẫu thiết kế <strong>{{ delete_SP.ten_san_pham }}</strong> khỏi danh mục trưng bày Flagship?</p>
        </div>
        <div class="modal-foot">
          <button type="button" class="btn-cancel" @click="hienModalXoa = false">Hủy</button>
          <button type="button" class="btn-delete-confirm" @click="xoaSanPham">Xóa Ngay</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AdminSanPham',
  data() {
    return {
      list_san_pham: [
        { id: 1, ma_san_pham: 'WA-B224-SND', ten_san_pham: 'Blazer Linen Thắt Eo Cổ V', danh_muc_id: 1, gia_ban: 1190000, gia_goc: 1450000, so_luong_ton: 21, da_ban: 38, hinh_anh_chinh: 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=120&q=80', mo_ta_ngan: 'Be cát tự nhiên', trang_thai: 'dang_ban', category: { ten_danh_muc: 'Blazer & Vest' } },
        { id: 2, ma_san_pham: 'WA-DR18-NOIR', ten_san_pham: 'Đầm Maxi Lụa Satin Xẻ Tà', danh_muc_id: 2, gia_ban: 1490000, gia_goc: 1800000, so_luong_ton: 17, da_ban: 29, hinh_anh_chinh: 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=120&q=80', mo_ta_ngan: 'Đen Noir óng', trang_thai: 'dang_ban', category: { ten_danh_muc: 'Đầm Dạ Tiệc' } },
        { id: 3, ma_san_pham: 'WA-SH07-WHT', ten_san_pham: 'Sơ mi Poplin Dáng Rộng Oversize', danh_muc_id: 3, gia_ban: 890000, gia_goc: 1100000, so_luong_ton: 35, da_ban: 24, hinh_anh_chinh: 'https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=120&q=80', mo_ta_ngan: 'Trắng tinh khiết', trang_thai: 'dang_ban', category: { ten_danh_muc: 'Sơ Mi & Poplin' } },
        { id: 4, ma_san_pham: 'WA-TR09-OLV', ten_san_pham: 'Quần Tây Xếp Ly Ống Rộng', danh_muc_id: 4, gia_ban: 1090000, gia_goc: 1350000, so_luong_ton: 15, da_ban: 21, hinh_anh_chinh: 'https://images.unsplash.com/photo-1542272604-780c96856592?auto=format&fit=crop&w=120&q=80', mo_ta_ngan: 'Xanh Olive Trầm', trang_thai: 'dang_ban', category: { ten_danh_muc: 'Quần Tây & Váy' } },
        { id: 5, ma_san_pham: 'WA-BM05-KHK', ten_san_pham: 'Áo Khoác Bomber Kaki Hai Mặt', danh_muc_id: 5, gia_ban: 1650000, gia_goc: 2000000, so_luong_ton: 0, da_ban: 18, hinh_anh_chinh: 'https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=120&q=80', mo_ta_ngan: 'Rêu Olive', trang_thai: 'het_hang', category: { ten_danh_muc: 'Áo Khoác Thu Đông' } }
      ],
      tim_kiem: '',
      loc_danh_muc: '',
      loc_trang_thai: '',
      is_loading: false,
      create_SP: {
        ma_san_pham: '',
        ten_san_pham: '',
        danh_muc_id: 1,
        gia_ban: 890000,
        gia_goc: 1200000,
        so_luong_ton: 20,
        hinh_anh_chinh: 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=400&q=80',
        mo_ta_ngan: '',
        trang_thai: 'dang_ban',
        noi_bat: false
      },
      edit_SP: {
        id: '',
        ma_san_pham: '',
        ten_san_pham: '',
        danh_muc_id: 1,
        gia_ban: 0,
        gia_goc: 0,
        so_luong_ton: 0,
        hinh_anh_chinh: '',
        mo_ta_ngan: '',
        trang_thai: 'dang_ban',
        noi_bat: false
      },
      delete_SP: {
        id: '',
        ma_san_pham: '',
        ten_san_pham: ''
      },
      hienModalSanPham: false,
      hienModalXoa: false,
      isEdit: false
    };
  },
  computed: {
    active_form() {
      return this.isEdit ? this.edit_SP : this.create_SP;
    },
    list_san_pham_filter() {
      return this.list_san_pham.filter(item => {
        // Tìm kiếm từ khóa
        let khopTuKhoa = true;
        if (this.tim_kiem && this.tim_kiem.trim()) {
          const kw = this.tim_kiem.trim().toLowerCase();
          khopTuKhoa = (item.ten_san_pham && item.ten_san_pham.toLowerCase().includes(kw)) ||
                       (item.ma_san_pham && item.ma_san_pham.toLowerCase().includes(kw)) ||
                       (item.mo_ta_ngan && item.mo_ta_ngan.toLowerCase().includes(kw));
        }

        // Lọc danh mục
        let khopDanhMuc = true;
        if (this.loc_danh_muc) {
          khopDanhMuc = String(item.danh_muc_id) === String(this.loc_danh_muc);
        }

        // Lọc trạng thái
        let khopTrangThai = true;
        if (this.loc_trang_thai) {
          khopTrangThai = item.trang_thai === this.loc_trang_thai;
        }

        return khopTuKhoa && khopDanhMuc && khopTrangThai;
      });
    }
  },
  mounted() {
    this.loadData();
  },
  methods: {
    dinhDangTien(so) {
      if (!so) return '0đ';
      return Number(so).toLocaleString('vi-VN') + 'đ';
    },

    datLaiBoLoc() {
      this.tim_kiem = '';
      this.loc_danh_muc = '';
      this.loc_trang_thai = '';
    },

    loadData() {
      this.is_loading = true;
      axios
        .get('http://127.0.0.1:8000/api/admin/san-pham/get-data')
        .then(res => {
          if (res.data && res.data.data && res.data.data.length > 0) {
            this.list_san_pham = res.data.data;
          }
        })
        .catch(err => {
          console.error('Lỗi tải danh sách sản phẩm:', err);
        })
        .finally(() => {
          this.is_loading = false;
        });
    },

    moModalThem() {
      this.isEdit = false;
      this.create_SP = {
        ma_san_pham: 'WA-NEW-' + Math.floor(100 + Math.random() * 900),
        ten_san_pham: '',
        danh_muc_id: 1,
        gia_ban: 890000,
        gia_goc: 1200000,
        so_luong_ton: 20,
        hinh_anh_chinh: 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=400&q=80',
        mo_ta_ngan: 'Lụa tự nhiên Atelier',
        trang_thai: 'dang_ban'
      };
      this.hienModalSanPham = true;
    },

    themSanPham() {
      if (!this.create_SP.ten_san_pham || !this.create_SP.ten_san_pham.trim()) {
        const msg = 'Vui lòng nhập tên sản phẩm thời trang!';
        this.$toast ? this.$toast.warning(msg) : alert(msg);
        return;
      }

      axios
        .post('http://127.0.0.1:8000/api/admin/san-pham/create', this.create_SP)
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            const msg = res.data.message || 'Thêm sản phẩm thành công!';
            this.$toast ? this.$toast.success(msg) : alert(msg);
            this.hienModalSanPham = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Thêm sản phẩm thất bại!';
            this.$toast ? this.$toast.error(msg) : alert(msg);
          }
        })
        .catch(err => {
          console.error('Lỗi thêm sản phẩm:', err);
          const errors = err.response?.data?.errors;
          let msg = err.response?.data?.message || 'Có lỗi xảy ra khi thêm sản phẩm!';
          if (errors) {
            const firstKey = Object.keys(errors)[0];
            msg = errors[firstKey][0] || msg;
          }
          this.$toast ? this.$toast.error(msg) : alert(msg);
        });
    },

    moModalSua(item) {
      this.isEdit = true;
      this.edit_SP = {
        id: item.id,
        ma_san_pham: item.ma_san_pham,
        ten_san_pham: item.ten_san_pham,
        danh_muc_id: item.danh_muc_id,
        gia_ban: item.gia_ban,
        gia_goc: item.gia_goc,
        so_luong_ton: item.so_luong_ton,
        hinh_anh_chinh: item.hinh_anh_chinh,
        mo_ta_ngan: item.mo_ta_ngan,
        trang_thai: item.trang_thai,
        noi_bat: item.noi_bat || false
      };
      this.hienModalSanPham = true;
    },

    updateSanPham() {
      if (!this.edit_SP.ten_san_pham || !this.edit_SP.ten_san_pham.trim()) {
        const msg = 'Tên sản phẩm không được để trống!';
        this.$toast ? this.$toast.warning(msg) : alert(msg);
        return;
      }

      axios
        .post('http://127.0.0.1:8000/api/admin/san-pham/update', this.edit_SP)
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            const msg = res.data.message || 'Cập nhật sản phẩm thành công!';
            this.$toast ? this.$toast.success(msg) : alert(msg);
            this.hienModalSanPham = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Cập nhật thất bại!';
            this.$toast ? this.$toast.error(msg) : alert(msg);
          }
        })
        .catch(err => {
          console.error('Lỗi cập nhật sản phẩm:', err);
          const errors = err.response?.data?.errors;
          let msg = err.response?.data?.message || 'Có lỗi xảy ra khi cập nhật!';
          if (errors) {
            const firstKey = Object.keys(errors)[0];
            msg = errors[firstKey][0] || msg;
          }
          this.$toast ? this.$toast.error(msg) : alert(msg);
        });
    },

    doiTrangThai(item) {
      const trang_thai_moi = item.trang_thai === 'dang_ban' ? 'tam_an' : 'dang_ban';
      axios
        .post('http://127.0.0.1:8000/api/admin/san-pham/change-status', { id: item.id, trang_thai: trang_thai_moi })
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            if (this.$toast) {
              this.$toast.success(res.data.message || 'Đổi trạng thái thành công!');
            }
            item.trang_thai = trang_thai_moi;
          } else {
            if (this.$toast) {
              this.$toast.error(res.data.message || 'Đổi trạng thái thất bại!');
            }
          }
        })
        .catch(err => {
          console.error('Lỗi đổi trạng thái:', err);
          const msg = err.response?.data?.message || 'Có lỗi xảy ra khi đổi trạng thái!';
          if (this.$toast) {
            this.$toast.error(msg);
          }
        });
    },

    moModalXoa(item) {
      this.delete_SP = {
        id: item.id,
        ma_san_pham: item.ma_san_pham,
        ten_san_pham: item.ten_san_pham
      };
      this.hienModalXoa = true;
    },

    xoaSanPham() {
      axios
        .post('http://127.0.0.1:8000/api/admin/san-pham/delete', { id: this.delete_SP.id })
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            const msg = res.data.message || 'Xóa sản phẩm thành công!';
            this.$toast ? this.$toast.success(msg) : alert(msg);
            this.hienModalXoa = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Xóa sản phẩm thất bại!';
            this.$toast ? this.$toast.error(msg) : alert(msg);
          }
        })
        .catch(err => {
          console.error('Lỗi xóa sản phẩm:', err);
          const msg = err.response?.data?.message || 'Có lỗi xảy ra khi xóa sản phẩm!';
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
  align-items: center;
  margin-bottom: 24px;
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
  transition: all 0.2s;
}
.btn-secondary-atelier:hover {
  background: #f8fafc;
  border-color: #cbd5e1;
}
.btn-primary-atelier {
  background: #0f172a;
  border: 1px solid #0f172a;
  color: #f8fafc;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
  transition: all 0.2s;
}
.btn-primary-atelier:hover {
  background: #1e293b;
}
.shortcut {
  background: rgba(255, 255, 255, 0.2);
  padding: 2px 6px;
  border-radius: 4px;
  font-size: 11px;
}

.filter-bar {
  background: #ffffff;
  padding: 16px 20px;
  border-radius: 12px;
  border: 1px solid #edf2f7;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
  margin-bottom: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
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
  padding: 10px 14px;
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
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
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
.prod-cell {
  display: flex;
  align-items: center;
  gap: 14px;
}
.prod-thumb {
  width: 48px;
  height: 56px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid #edf2f7;
}
.prod-name {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #0f172a;
}
.prod-sku-tag {
  display: block;
  font-size: 12px;
  color: #8b6b4e;
  margin-top: 2px;
}
.cat-pill {
  background: #f1f5f9;
  color: #475569;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}
.price-main {
  display: block;
  font-size: 14px;
  font-weight: 700;
  color: #0f172a;
}
.price-orig {
  font-size: 12px;
  color: #94a3b8;
  text-decoration: line-through;
}
.size-group {
  display: inline-flex;
  gap: 6px;
}
.size-tag {
  font-size: 11px;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 4px;
}
.tag-ok {
  background: #f0fdf4;
  color: #16a34a;
}
.tag-low {
  background: #fffbeb;
  color: #d97706;
}
.tag-out {
  background: #fef2f2;
  color: #dc2626;
}
.badge-status {
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
.status-out {
  background: #fee2e2;
  color: #b91c1c;
}
.status-pending {
  background: #f1f5f9;
  color: #64748b;
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
.modal-small {
  max-width: 420px;
}
.modal-head {
  padding: 18px 24px;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.modal-head h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
}
.modal-head .btn-close {
  background: none;
  border: none;
  font-size: 22px;
  color: #94a3b8;
  cursor: pointer;
}
.modal-body {
  padding: 24px;
}
.form-row {
  display: flex;
  gap: 16px;
  margin-bottom: 16px;
}
.form-col {
  flex: 1;
}
.form-group {
  margin-bottom: 16px;
}
.form-group label, .form-col label {
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
.atelier-input {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 14px;
  outline: none;
  box-sizing: border-box;
}
.atelier-input:focus {
  border-color: #0f172a;
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
.btn-delete-confirm {
  background: #dc2626;
  color: #ffffff;
  border: none;
  padding: 9px 20px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}
</style>
