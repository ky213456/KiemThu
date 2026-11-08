<template>
  <div class="quan-ly-danh-muc" data-testid="page-quan-ly-danh-muc">
    <div class="header-action">
      <div>
        <h2 class="title" data-testid="title-quan-ly-danh-muc">
          <i class="bi bi-collection-fill text-primary"></i> Quản Lý Danh Mục Áo Quần
        </h2>
        <p class="subtitle">Quản lý các nhóm thời trang: Áo nam, Áo nữ, Quần nam, Váy đầm...</p>
      </div>
      <button type="button" class="btn btn-primary" data-testid="btn-them-danh-muc" @click="moModalThem">
        <i class="bi bi-plus-circle-fill"></i> Thêm Danh Mục Mới
      </button>
    </div>

    <!-- Thanh tìm kiếm -->
    <div class="filter-card">
      <div class="search-box">
        <label for="tim-kiem-dm"><i class="bi bi-search"></i> Tìm kiếm danh mục:</label>
        <input
          id="tim-kiem-dm"
          type="text"
          v-model="tim_kiem"
          placeholder="Nhập tên danh mục hoặc slug (ví dụ: ao-nam)..."
          class="form-control"
          data-testid="input-tim-kiem-danh-muc"
        />
      </div>
    </div>

    <!-- Bảng danh mục -->
    <div class="table-container">
      <table class="data-table" data-testid="table-danh-muc">
        <thead>
          <tr>
            <th width="60" class="text-center">STT</th>
            <th width="80" class="text-center">Ảnh</th>
            <th>Tên Danh Mục</th>
            <th width="180">Đường Dẫn (Slug)</th>
            <th>Mô Tả</th>
            <th width="140" class="text-center">Trạng Thái</th>
            <th width="140" class="text-center">Hành Động</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in list_danh_muc_filter" :key="item.id || index" :data-testid="'row-danh-muc-' + (index + 1)">
            <td class="text-center">{{ index + 1 }}</td>
            <td class="text-center">
              <img :src="item.hinh_anh || 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=400&q=80'" :alt="item.ten_danh_muc" class="img-thumb" />
            </td>
            <td><strong :data-testid="'ten-dm-' + (index + 1)">{{ item.ten_danh_muc }}</strong></td>
            <td><code class="slug-code" :data-testid="'slug-dm-' + (index + 1)">{{ item.duong_dan }}</code></td>
            <td class="text-muted">{{ item.mo_ta || 'Chưa có mô tả' }}</td>
            <td class="text-center">
              <button
                type="button"
                :class="['status-badge', (item.trang_thai == 1 || item.trang_thai == true) ? 'status-active' : 'status-inactive']"
                @click="doiTrangThai(item)"
                :title="'Click để đổi trạng thái'"
              >
                <i :class="(item.trang_thai == 1 || item.trang_thai == true) ? 'bi bi-eye-fill' : 'bi bi-eye-slash-fill'"></i>
                {{ (item.trang_thai == 1 || item.trang_thai == true) ? 'Hiển thị' : 'Tạm ẩn' }}
              </button>
            </td>
            <td class="text-center action-buttons">
              <button type="button" class="btn-sm btn-edit" :data-testid="'btn-sua-dm-' + (index + 1)" @click="moModalSua(item)">
                <i class="bi bi-pencil-square"></i> Sửa
              </button>
              <button type="button" class="btn-sm btn-delete" :data-testid="'btn-xoa-dm-' + (index + 1)" @click="moModalXoa(item)">
                <i class="bi bi-trash3-fill"></i> Xóa
              </button>
            </td>
          </tr>
          <tr v-if="list_danh_muc_filter.length === 0">
            <td colspan="7" class="text-center py-4 text-muted">
              {{ is_loading ? 'Đang tải dữ liệu...' : 'Không tìm thấy danh mục nào phù hợp' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- MODAL THÊM / CẬP NHẬT DANH MỤC -->
    <div v-if="hienModalDanhMuc" class="modal-backdrop" data-testid="modal-danh-muc" @click.self="hienModalDanhMuc = false">
      <div class="modal-dialog">
        <div class="modal-header">
          <h3 data-testid="modal-title-dm">{{ isEdit ? 'Cập Nhật Danh Mục' : 'Thêm Mới Danh Mục' }}</h3>
          <button type="button" class="btn-close" data-testid="btn-dong-modal-dm" @click="hienModalDanhMuc = false">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="dm-ten" class="required">Tên danh mục:</label>
            <input
              id="dm-ten"
              type="text"
              v-model="active_form.ten_danh_muc"
              placeholder="Ví dụ: Áo Nam, Váy & Đầm..."
              class="form-control"
              data-testid="input-ten-danh-muc"
            />
          </div>

          <div class="form-group">
            <label for="dm-slug">Đường dẫn (Slug):</label>
            <input
              id="dm-slug"
              type="text"
              v-model="active_form.duong_dan"
              placeholder="Tự động tạo nếu để trống (ví dụ: ao-nam)"
              class="form-control"
              data-testid="input-slug-danh-muc"
            />
          </div>

          <div class="form-group">
            <label for="dm-hinh-anh">Link hình ảnh đại diện:</label>
            <input
              id="dm-hinh-anh"
              type="text"
              v-model="active_form.hinh_anh"
              placeholder="https://..."
              class="form-control"
              data-testid="input-hinh-anh-danh-muc"
            />
          </div>

          <div class="form-group">
            <label for="dm-mo-ta">Mô tả danh mục:</label>
            <textarea
              id="dm-mo-ta"
              rows="3"
              v-model="active_form.mo_ta"
              class="form-control"
              placeholder="Nhập ghi chú hoặc mô tả về danh mục..."
              data-testid="input-mo-ta-danh-muc"
            ></textarea>
          </div>

          <div class="form-group">
            <label class="checkbox-label">
              <input
                type="checkbox"
                v-model="active_form.trang_thai"
                :true-value="1"
                :false-value="0"
                data-testid="checkbox-trang-thai-dm"
              />
              Hiển thị danh mục ra ngoài trang người dùng
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-testid="btn-huy-modal-dm" @click="hienModalDanhMuc = false">Hủy Bỏ</button>
          <button
            type="button"
            class="btn btn-primary"
            data-testid="btn-luu-danh-muc"
            @click="isEdit ? updateDanhMuc() : themDanhMuc()"
          >
            {{ isEdit ? 'Lưu Thay Đổi' : 'Thêm Mới' }}
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL XÁC NHẬN XÓA -->
    <div v-if="hienModalXoa" class="modal-backdrop" data-testid="modal-xac-nhan-xoa-dm" @click.self="hienModalXoa = false">
      <div class="modal-dialog modal-sm">
        <div class="modal-header">
          <h3>Xác Nhận Xóa Danh Mục</h3>
          <button type="button" class="btn-close" @click="hienModalXoa = false">&times;</button>
        </div>
        <div class="modal-body">
          <p>Bạn có chắc chắn muốn xóa danh mục <strong>{{ delete_DM.ten_danh_muc }}</strong> khỏi hệ thống không?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="hienModalXoa = false">Hủy</button>
          <button type="button" class="btn btn-danger" data-testid="btn-dong-y-xoa-dm" @click="xoaDanhMuc">Xóa Ngay</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AdminDanhMuc',
  data() {
    return {
      list_danh_muc: [
        { id: 1, ten_danh_muc: 'Áo Nam', duong_dan: 'ao-nam', mo_ta: 'Áo thun, áo polo, sơ mi nam', hinh_anh: 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=400&q=80', trang_thai: 1 },
        { id: 2, ten_danh_muc: 'Áo Nữ', duong_dan: 'ao-nu', mo_ta: 'Áo kiểu, croptop, sơ mi nữ', hinh_anh: 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=400&q=80', trang_thai: 1 },
        { id: 3, ten_danh_muc: 'Quần Nam', duong_dan: 'quan-nam', mo_ta: 'Quần jeans, quần âu, kaki', hinh_anh: 'https://images.unsplash.com/photo-1542272604-780c96856592?auto=format&fit=crop&w=400&q=80', trang_thai: 1 },
        { id: 4, ten_danh_muc: 'Váy & Đầm Nữ', duong_dan: 'vay-dam-nu', mo_ta: 'Đầm dạo phố, đầm dạ tiệc', hinh_anh: 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=400&q=80', trang_thai: 1 },
        { id: 5, ten_danh_muc: 'Áo Khoác & Hoodie', duong_dan: 'ao-khoac-hoodie', mo_ta: 'Áo khoác gió, hoodie unisex', hinh_anh: 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=400&q=80', trang_thai: 1 }
      ],
      tim_kiem: '',
      is_loading: false,
      create_DM: {
        ten_danh_muc: '',
        duong_dan: '',
        mo_ta: '',
        hinh_anh: 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=400&q=80',
        trang_thai: 1
      },
      edit_DM: {
        id: '',
        ten_danh_muc: '',
        duong_dan: '',
        mo_ta: '',
        hinh_anh: '',
        trang_thai: 1
      },
      delete_DM: {
        id: '',
        ten_danh_muc: ''
      },
      hienModalDanhMuc: false,
      hienModalXoa: false,
      isEdit: false
    };
  },
  computed: {
    active_form() {
      return this.isEdit ? this.edit_DM : this.create_DM;
    },
    list_danh_muc_filter() {
      if (!this.tim_kiem || !this.tim_kiem.trim()) {
        return this.list_danh_muc;
      }
      const keyword = this.tim_kiem.trim().toLowerCase();
      return this.list_danh_muc.filter(item =>
        (item.ten_danh_muc && item.ten_danh_muc.toLowerCase().includes(keyword)) ||
        (item.duong_dan && item.duong_dan.toLowerCase().includes(keyword))
      );
    }
  },
  mounted() {
    this.loadData();
  },
  methods: {
    loadData() {
      this.is_loading = true;
      axios
        .get('http://127.0.0.1:8000/api/admin/danh-muc/get-data')
        .then(res => {
          if (res.data && res.data.data && res.data.data.length > 0) {
            this.list_danh_muc = res.data.data;
          }
        })
        .catch(err => {
          console.error('Lỗi tải danh sách danh mục:', err);
        })
        .finally(() => {
          this.is_loading = false;
        });
    },

    moModalThem() {
      this.isEdit = false;
      this.create_DM = {
        ten_danh_muc: '',
        duong_dan: '',
        mo_ta: '',
        hinh_anh: 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=400&q=80',
        trang_thai: 1
      };
      this.hienModalDanhMuc = true;
    },

    themDanhMuc() {
      if (!this.create_DM.ten_danh_muc || !this.create_DM.ten_danh_muc.trim()) {
        if (this.$toast) {
          this.$toast.warning('Vui lòng nhập tên danh mục!');
        } else {
          alert('Vui lòng nhập tên danh mục!');
        }
        return;
      }

      axios
        .post('http://127.0.0.1:8000/api/admin/danh-muc/create', this.create_DM)
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            if (this.$toast) {
              this.$toast.success(res.data.message || 'Thêm danh mục thành công!');
            } else {
              alert(res.data.message || 'Thêm danh mục thành công!');
            }
            this.hienModalDanhMuc = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Thêm mới thất bại!';
            if (this.$toast) {
              this.$toast.error(msg);
            } else {
              alert(msg);
            }
          }
        })
        .catch(err => {
          console.error('Lỗi thêm danh mục:', err);
          const errors = err.response?.data?.errors;
          let msg = err.response?.data?.message || 'Có lỗi xảy ra khi thêm danh mục!';
          if (errors) {
            const firstKey = Object.keys(errors)[0];
            msg = errors[firstKey][0] || msg;
          }
          if (this.$toast) {
            this.$toast.error(msg);
          } else {
            alert(msg);
          }
        });
    },

    moModalSua(item) {
      this.isEdit = true;
      this.edit_DM = {
        id: item.id,
        ten_danh_muc: item.ten_danh_muc,
        duong_dan: item.duong_dan,
        mo_ta: item.mo_ta,
        hinh_anh: item.hinh_anh,
        trang_thai: item.trang_thai ? 1 : 0
      };
      this.hienModalDanhMuc = true;
    },

    updateDanhMuc() {
      if (!this.edit_DM.ten_danh_muc || !this.edit_DM.ten_danh_muc.trim()) {
        if (this.$toast) {
          this.$toast.warning('Tên danh mục không được để trống!');
        } else {
          alert('Tên danh mục không được để trống!');
        }
        return;
      }

      axios
        .post('http://127.0.0.1:8000/api/admin/danh-muc/update', this.edit_DM)
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            if (this.$toast) {
              this.$toast.success(res.data.message || 'Cập nhật danh mục thành công!');
            } else {
              alert(res.data.message || 'Cập nhật danh mục thành công!');
            }
            this.hienModalDanhMuc = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Cập nhật danh mục thất bại!';
            if (this.$toast) {
              this.$toast.error(msg);
            } else {
              alert(msg);
            }
          }
        })
        .catch(err => {
          console.error('Lỗi cập nhật danh mục:', err);
          const errors = err.response?.data?.errors;
          let msg = err.response?.data?.message || 'Có lỗi xảy ra khi cập nhật danh mục!';
          if (errors) {
            const firstKey = Object.keys(errors)[0];
            msg = errors[firstKey][0] || msg;
          }
          if (this.$toast) {
            this.$toast.error(msg);
          } else {
            alert(msg);
          }
        });
    },

    doiTrangThai(item) {
      const trang_thai_moi = (item.trang_thai == 1 || item.trang_thai == true) ? 0 : 1;
      axios
        .post('http://127.0.0.1:8000/api/admin/danh-muc/change-status', { id: item.id, trang_thai: trang_thai_moi })
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
      this.delete_DM = {
        id: item.id,
        ten_danh_muc: item.ten_danh_muc
      };
      this.hienModalXoa = true;
    },

    xoaDanhMuc() {
      axios
        .post('http://127.0.0.1:8000/api/admin/danh-muc/delete', { id: this.delete_DM.id })
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            if (this.$toast) {
              this.$toast.success(res.data.message || 'Xóa danh mục thành công!');
            } else {
              alert(res.data.message || 'Xóa danh mục thành công!');
            }
            this.hienModalXoa = false;
            this.loadData();
          } else {
            const msg = res.data.message || 'Xóa danh mục thất bại!';
            if (this.$toast) {
              this.$toast.error(msg);
            } else {
              alert(msg);
            }
          }
        })
        .catch(err => {
          console.error('Lỗi xóa danh mục:', err);
          const msg = err.response?.data?.message || 'Có lỗi xảy ra khi xóa danh mục!';
          if (this.$toast) {
            this.$toast.error(msg);
          } else {
            alert(msg);
          }
        });
    }
  }
};
</script>

<style scoped>
.quan-ly-danh-muc {
  padding: 24px;
  background-color: #f8fafc;
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  color: #1e293b;
}
.header-action { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.title { margin: 0; font-size: 24px; font-weight: 700; }
.subtitle { margin: 4px 0 0; font-size: 14px; color: #64748b; }

.btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 18px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  border: 1px solid transparent;
}
.btn-primary { background: #2563eb; color: #fff; }
.btn-primary:hover { background: #1d4ed8; }
.btn-secondary { background: #e2e8f0; color: #334155; }
.btn-danger { background: #ef4444; color: #fff; }

.filter-card {
  background: #ffffff;
  padding: 16px 20px;
  border-radius: 10px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
  margin-bottom: 20px;
}
.search-box label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #475569; }
.form-control {
  width: 100%;
  padding: 9px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 14px;
  outline: none;
  box-sizing: border-box;
}
.form-control:focus { border-color: #2563eb; }

.table-container {
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
  overflow: hidden;
}
.data-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}
.data-table th {
  background: #f1f5f9;
  padding: 12px 16px;
  text-align: left;
  font-weight: 600;
  color: #475569;
  border-bottom: 1px solid #e2e8f0;
}
.data-table td {
  padding: 14px 16px;
  border-bottom: 1px solid #f1f5f9;
  vertical-align: middle;
}
.text-center { text-align: center; }
.text-muted { color: #64748b; font-size: 13px; }
.img-thumb { width: 44px; height: 44px; object-fit: cover; border-radius: 6px; }
.slug-code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 12px; color: #0284c7; }

.status-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  border: none;
  cursor: pointer;
}
.status-active { background: #dcfce7; color: #15803d; }
.status-inactive { background: #fee2e2; color: #b91c1c; }

.action-buttons { display: flex; gap: 8px; justify-content: center; }
.btn-sm { padding: 6px 12px; font-size: 13px; border-radius: 6px; border: none; cursor: pointer; font-weight: 500; }
.btn-edit { background: #e0e7ff; color: #4338ca; }
.btn-delete { background: #fee2e2; color: #dc2626; }

/* Modal styles */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.modal-dialog {
  background: #ffffff;
  border-radius: 12px;
  width: 90%;
  max-width: 520px;
  box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
  overflow: hidden;
}
.modal-sm { max-width: 400px; }
.modal-header {
  padding: 18px 24px;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; }
.btn-close { background: none; border: none; font-size: 22px; cursor: pointer; color: #94a3b8; }
.modal-body { padding: 20px 24px; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #334155; }
.required::after { content: " *"; color: #ef4444; }
.checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: normal; cursor: pointer; }
.modal-footer {
  padding: 14px 24px;
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
</style>
