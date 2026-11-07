<template>
  <div class="atelier-page" data-testid="page-quan-ly-nguoi-dung">
    <!-- HEADER TRANG -->
    <div class="page-header">
      <div>
        <div class="breadcrumb-meta">HỆ THỐNG &amp; NHÂN SỰ &gt; QUẢN LÝ TÀI KHOẢN &amp; KHÁCH HÀNG VIP</div>
        <h1 class="page-title" data-testid="title-quan-ly-nguoi-dung">Quản Lý Người Dùng &amp; Đặc Quyền</h1>
        <p class="page-sub">Danh sách nhân sự Flagship, stylist tư vấn và danh bạ khách hàng thân thiết Maison Atelier</p>
      </div>

      <div class="page-actions">
        <button type="button" class="btn-secondary-atelier" @click="loadData">
          <span>🔄</span> Tải Lại Dữ Liệu
        </button>
        <button type="button" class="btn-primary-atelier" data-testid="btn-them-user" @click="moModalThem">
          <span>+</span> Thêm Tài Khoản Mới <span class="shortcut">F2</span>
        </button>
      </div>
    </div>

    <!-- BỘ LỌC TĨNH SANG TRỌNG -->
    <div class="filter-bar">
      <div class="search-field">
        <span class="icon">🔍</span>
        <input
          id="tim-kiem-user"
          type="text"
          v-model="tim_kiem"
          placeholder="Tìm theo tên thành viên, email, số điện thoại..."
          class="input-search"
          data-testid="input-tim-kiem-user"
        />
      </div>

      <div class="filter-dropdowns">
        <select id="loc-vai-tro" v-model="loc_vai_tro" class="select-filter" data-testid="select-loc-vai-tro">
          <option value="">Tất cả phân quyền &amp; vai trò</option>
          <option value="quan_tri">Quản Trị Viên (Store Manager)</option>
          <option value="nhan_vien">Nhân Viên Bán Hàng (Stylist)</option>
          <option value="khach_hang">Khách Hàng (VIP &amp; Regular)</option>
        </select>

        <button type="button" class="btn-reset" @click="tim_kiem = ''; loc_vai_tro = ''">Đặt lại</button>
      </div>
    </div>

    <!-- BẢNG DANH SÁCH NGƯỜI DÙNG PHONG CÁCH MAISON ATELIER -->
    <div class="table-card">
      <table class="atelier-data-table" data-testid="table-nguoi-dung">
        <thead>
          <tr>
            <th width="50" class="text-center">STT</th>
            <th>HỒ SƠ THÀNH VIÊN</th>
            <th width="140">LIÊN HỆ</th>
            <th width="160">PHÂN HẠNG / VAI TRÒ</th>
            <th width="150">ĐỊA CHỈ</th>
            <th width="130">TRẠNG THÁI</th>
            <th width="140" class="text-center">HÀNH ĐỘNG</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in list_user_filter" :key="item.id || index" :data-testid="'row-user-' + (index + 1)">
            <td class="text-center text-muted">{{ String(index + 1).padStart(2, '0') }}</td>
            <td>
              <div class="user-cell">
                <img :src="item.anh_dai_dien || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80'" alt="Avatar" class="user-avatar" />
                <div>
                  <strong class="user-name" :data-testid="'name-user-' + (index + 1)">{{ item.ho_ten }}</strong>
                  <div class="user-sub">
                    <span class="user-email" :data-testid="'email-user-' + (index + 1)">{{ item.email }}</span>
                  </div>
                </div>
              </div>
            </td>
            <td>
              <div class="contact-box">
                <span class="phone-num">{{ item.so_dien_thoai || '0988 888 888' }}</span>
              </div>
            </td>
            <td>
              <span :class="['role-badge', item.vai_tro === 'quan_tri' ? 'role-admin' : (item.vai_tro === 'nhan_vien' ? 'role-staff' : 'role-vip-diamond')]" :data-testid="'role-user-' + (index + 1)">
                {{ item.vai_tro === 'quan_tri' ? 'Quản Trị Viên' : (item.vai_tro === 'nhan_vien' ? 'Nhân Viên' : 'Khách Hàng') }}
              </span>
            </td>
            <td>
              <span class="spending-text text-muted">{{ item.dia_chi || 'Quận 1, TP.HCM' }}</span>
            </td>
            <td>
              <button
                type="button"
                :class="['badge-status', (item.trang_thai == 1 || item.trang_thai == true) ? 'status-active' : 'status-locked']"
                :data-testid="'status-user-' + (index + 1)"
                @click="doiTrangThai(item)"
              >
                {{ (item.trang_thai == 1 || item.trang_thai == true) ? 'Hoạt động' : 'Bị khóa' }}
              </button>
            </td>
            <td class="text-center">
              <button type="button" class="btn-action edit" :data-testid="'btn-sua-user-' + (index + 1)" @click="moModalSua(item)">Sửa</button>
              <button type="button" class="btn-action lock" :data-testid="'btn-khoa-user-' + (index + 1)" @click="moModalXoa(item)">Xóa</button>
            </td>
          </tr>
          <tr v-if="list_user_filter.length === 0">
            <td colspan="7" class="text-center py-4 text-muted">
              {{ is_loading ? 'Đang tải danh sách người dùng...' : 'Không tìm thấy tài khoản nào' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- MODAL POPUP THÊM / CẬP NHẬT NGƯỜI DÙNG -->
    <div v-if="hienModalUser" class="modal-backdrop" data-testid="modal-user" @click.self="hienModalUser = false">
      <div class="atelier-modal">
        <div class="modal-head">
          <div>
            <div class="modal-subtitle-tag">QUẢN LÝ TÀI KHOẢN VÀ THẺ VIP</div>
            <h3 class="modal-main-title" data-testid="modal-title-user">{{ isEdit ? 'Cập Nhật Tài Khoản' : 'Thêm Thành Viên Mới' }}</h3>
          </div>
          <button type="button" class="btn-close-atelier" data-testid="btn-dong-modal-user" @click="hienModalUser = false">&times;</button>
        </div>

        <div class="modal-body-atelier">
          <div class="form-grid-2">
            <div class="form-item">
              <label for="u-hoten" class="form-label required">Họ và tên thành viên:</label>
              <input id="u-hoten" type="text" v-model="active_form.ho_ten" placeholder="VD: Trần Mai Phương" class="form-input" data-testid="input-hoten-user" />
            </div>

            <div class="form-item">
              <label for="u-email" class="form-label required">Email liên lạc:</label>
              <input id="u-email" type="email" v-model="active_form.email" placeholder="client@example.com" class="form-input" data-testid="input-email-user" />
            </div>
          </div>

          <div class="form-grid-2">
            <div class="form-item">
              <label for="u-phone" class="form-label">Số điện thoại liên lạc:</label>
              <input id="u-phone" type="text" v-model="active_form.so_dien_thoai" placeholder="0912 345 678" class="form-input" data-testid="input-phone-user" />
            </div>

            <div class="form-item">
              <label for="u-role" class="form-label">Phân quyền vai trò:</label>
              <select id="u-role" v-model="active_form.vai_tro" class="form-select" data-testid="select-vaitro-user">
                <option value="khach_hang">Khách Hàng (Tích điểm VIP)</option>
                <option value="nhan_vien">Nhân Viên Bán Hàng (Stylist Flagship)</option>
                <option value="quan_tri">Quản Trị Viên (Store Manager)</option>
              </select>
            </div>
          </div>

          <div class="form-item">
            <label for="u-address" class="form-label">Địa chỉ:</label>
            <input id="u-address" type="text" v-model="active_form.dia_chi" placeholder="Quận 1, TP. Hồ Chí Minh" class="form-input" data-testid="input-address-user" />
          </div>

          <div class="checkbox-box">
            <label class="checkbox-label">
              <input type="checkbox" v-model="active_form.trang_thai" :true-value="1" :false-value="0" data-testid="checkbox-trangthai-user" />
              <span>Kích hoạt quyền sử dụng dịch vụ tại Boutique (Active)</span>
            </label>
          </div>
        </div>

        <div class="modal-foot-atelier">
          <button type="button" class="btn-cancel" @click="hienModalUser = false">Hủy Bỏ</button>
          <button
            type="button"
            class="btn-save"
            data-testid="btn-luu-user"
            @click="isEdit ? updateNguoiDung() : themNguoiDung()"
          >
            {{ isEdit ? 'Lưu Thay Đổi' : 'Lưu Hồ Sơ' }}
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL XÁC NHẬN XÓA TÀI KHOẢN -->
    <div v-if="hienModalXoa" class="modal-backdrop" @click.self="hienModalXoa = false">
      <div class="atelier-modal modal-sm">
        <div class="modal-head">
          <div>
            <div class="modal-subtitle-tag">CẢNH BÁO TÀI KHOẢN</div>
            <h3 class="modal-main-title">Xóa Người Dùng?</h3>
          </div>
          <button type="button" class="btn-close-atelier" @click="hienModalXoa = false">&times;</button>
        </div>
        <div class="modal-body-atelier">
          <p class="confirm-text">Bạn có chắc chắn muốn xóa tài khoản <strong>{{ delete_user.ho_ten }}</strong> khỏi hệ thống?</p>
        </div>
        <div class="modal-foot-atelier">
          <button type="button" class="btn-cancel" @click="hienModalXoa = false">Đóng</button>
          <button type="button" class="btn-danger-confirm" @click="xoaNguoiDung">Xóa Ngay</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AdminNguoiDung',
  data() {
    return {
      list_user: [
        { id: 1, ho_ten: 'Trần Mai Anh (Quản Lý Flagship)', email: 'admin@shop.com', so_dien_thoai: '0988 888 888', vai_tro: 'quan_tri', dia_chi: 'Quận 1, TP.HCM', trang_thai: 1, anh_dai_dien: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80' },
        { id: 2, ho_ten: 'Lê Hoàng Nam (Fashion Stylist)', email: 'staff@shop.com', so_dien_thoai: '0977 777 777', vai_tro: 'nhan_vien', dia_chi: 'Quận 3, TP.HCM', trang_thai: 1, anh_dai_dien: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80' },
        { id: 3, ho_ten: 'Chị Thảo My (VIP Diamond)', email: 'customer@shop.com', so_dien_thoai: '0912 345 678', vai_tro: 'khach_hang', dia_chi: 'Vinhome Ba Son, Q.1', trang_thai: 1, anh_dai_dien: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80' }
      ],
      tim_kiem: '',
      loc_vai_tro: '',
      is_loading: false,
      create_user: {
        ho_ten: '',
        email: '',
        so_dien_thoai: '',
        vai_tro: 'khach_hang',
        dia_chi: 'Quận 1, TP.HCM',
        mat_khau: '123456',
        trang_thai: 1
      },
      edit_user: {
        id: '',
        ho_ten: '',
        email: '',
        so_dien_thoai: '',
        vai_tro: 'khach_hang',
        dia_chi: '',
        trang_thai: 1
      },
      delete_user: {
        id: '',
        ho_ten: ''
      },
      hienModalUser: false,
      hienModalXoa: false,
      isEdit: false
    };
  },
  computed: {
    active_form() {
      return this.isEdit ? this.edit_user : this.create_user;
    },
    list_user_filter() {
      return this.list_user.filter(item => {
        let khopTuKhoa = true;
        if (this.tim_kiem && this.tim_kiem.trim()) {
          const kw = this.tim_kiem.trim().toLowerCase();
          khopTuKhoa = (item.ho_ten && item.ho_ten.toLowerCase().includes(kw)) ||
                       (item.email && item.email.toLowerCase().includes(kw)) ||
                       (item.so_dien_thoai && item.so_dien_thoai.includes(kw));
        }
        let khopVaiTro = true;
        if (this.loc_vai_tro) {
          khopVaiTro = item.vai_tro === this.loc_vai_tro;
        }
        return khopTuKhoa && khopVaiTro;
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
        .get('http://127.0.0.1:8000/api/admin/nguoi-dung/get-data')
        .then(res => {
          if (res.data && res.data.data && res.data.data.length > 0) {
            this.list_user = res.data.data;
          }
        })
        .catch(err => {
          console.error('Lỗi tải danh sách người dùng:', err);
        })
        .finally(() => {
          this.is_loading = false;
        });
    },

    moModalThem() {
      this.isEdit = false;
      this.create_user = {
        ho_ten: '',
        email: '',
        so_dien_thoai: '',
        vai_tro: 'khach_hang',
        dia_chi: 'Quận 1, TP.HCM',
        mat_khau: '123456',
        trang_thai: 1
      };
      this.hienModalUser = true;
    },

    themNguoiDung() {
      if (!this.create_user.ho_ten || !this.create_user.ho_ten.trim()) {
        const msg = 'Vui lòng nhập họ và tên!';
        this.$toast ? this.$toast.warning(msg) : alert(msg);
        return;
      }

      axios
        .post('http://127.0.0.1:8000/api/admin/nguoi-dung/create', this.create_user)
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            const msg = res.data.message || 'Thêm người dùng thành công!';
            this.$toast ? this.$toast.success(msg) : alert(msg);
            this.hienModalUser = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Thêm thất bại!';
            this.$toast ? this.$toast.error(msg) : alert(msg);
          }
        })
        .catch(err => {
          console.error('Lỗi thêm người dùng:', err);
          const msg = err.response?.data?.message || 'Có lỗi xảy ra!';
          this.$toast ? this.$toast.error(msg) : alert(msg);
        });
    },

    moModalSua(item) {
      this.isEdit = true;
      this.edit_user = {
        id: item.id,
        ho_ten: item.ho_ten,
        email: item.email,
        so_dien_thoai: item.so_dien_thoai,
        vai_tro: item.vai_tro,
        dia_chi: item.dia_chi,
        trang_thai: item.trang_thai ? 1 : 0
      };
      this.hienModalUser = true;
    },

    updateNguoiDung() {
      axios
        .post('http://127.0.0.1:8000/api/admin/nguoi-dung/update', this.edit_user)
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            const msg = res.data.message || 'Cập nhật tài khoản thành công!';
            this.$toast ? this.$toast.success(msg) : alert(msg);
            this.hienModalUser = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Cập nhật thất bại!';
            this.$toast ? this.$toast.error(msg) : alert(msg);
          }
        })
        .catch(err => {
          console.error('Lỗi cập nhật người dùng:', err);
          const msg = err.response?.data?.message || 'Có lỗi xảy ra!';
          this.$toast ? this.$toast.error(msg) : alert(msg);
        });
    },

    doiTrangThai(item) {
      const trang_thai_moi = item.trang_thai ? 0 : 1;
      axios
        .post('http://127.0.0.1:8000/api/admin/nguoi-dung/change-status', { id: item.id, trang_thai: trang_thai_moi })
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            if (this.$toast) this.$toast.success(res.data.message || 'Đổi trạng thái thành công!');
            item.trang_thai = trang_thai_moi;
          }
        })
        .catch(err => {
          console.error('Lỗi đổi trạng thái:', err);
        });
    },

    moModalXoa(item) {
      this.delete_user = {
        id: item.id,
        ho_ten: item.ho_ten
      };
      this.hienModalXoa = true;
    },

    xoaNguoiDung() {
      axios
        .post('http://127.0.0.1:8000/api/admin/nguoi-dung/delete', { id: this.delete_user.id })
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            const msg = res.data.message || 'Xóa người dùng thành công!';
            this.$toast ? this.$toast.success(msg) : alert(msg);
            this.hienModalXoa = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Xóa thất bại!';
            this.$toast ? this.$toast.error(msg) : alert(msg);
          }
        })
        .catch(err => {
          console.error('Lỗi xóa người dùng:', err);
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
.user-cell {
  display: flex;
  align-items: center;
  gap: 12px;
}
.user-avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #f1f5f9;
}
.user-name {
  display: block;
  font-weight: 600;
  color: #0f1422;
}
.user-sub {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 2px;
}
.user-email {
  font-size: 12px;
  color: #64748b;
}
.contact-box {
  display: flex;
  flex-direction: column;
}
.phone-num {
  font-weight: 600;
  color: #334155;
}
.role-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
}
.role-admin {
  background: #fdf2f8;
  color: #be185d;
}
.role-staff {
  background: #eff6ff;
  color: #1d4ed8;
}
.role-vip-diamond {
  background: #fefce8;
  color: #a16207;
}
.spending-text {
  font-size: 13px;
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
.status-locked {
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
.btn-action.lock {
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
.checkbox-box {
  margin-top: 10px;
}
.checkbox-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  cursor: pointer;
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
