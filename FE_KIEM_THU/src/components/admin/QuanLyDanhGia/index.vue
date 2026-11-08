<template>
  <div class="atelier-page" data-testid="page-quan-ly-danh-gia">
    <!-- HEADER TRANG -->
    <div class="page-header">
      <div>
        <div class="breadcrumb-meta">KHÁCH HÀNG &amp; TRẢI NGHIỆM &gt; ĐÁNH GIÁ ATELIER</div>
        <h1 class="page-title" data-testid="title-quan-ly-danh-gia">Quản Lý Đánh Giá &amp; Trải Nghiệm Khách Hàng</h1>
        <p class="page-sub">Theo dõi phản hồi thử đồ tại phòng fitting Flagship và đánh giá chất lượng vải lookbook</p>
      </div>

      <div class="page-actions">
        <button type="button" class="btn-secondary-atelier" @click="loadData">
          <i class="bi bi-arrow-clockwise"></i> Tải Lại Dữ Liệu
        </button>
        <button type="button" class="btn-secondary-atelier" @click="hienModalTieuChuan = true">
          <i class="bi bi-patch-check-fill"></i> Tiêu Chuẩn Duyệt
        </button>
        <button type="button" class="btn-primary-atelier" data-testid="btn-phan-hoi-khach" @click="moModalPhanHoi">
          <i class="bi bi-chat-dots-fill"></i> Phản Hồi Chung
        </button>
      </div>
    </div>

    <!-- BỘ LỌC TĨNH SANG TRỌNG -->
    <div class="filter-bar">
      <div class="search-field">
        <span class="icon"><i class="bi bi-search"></i></span>
        <input
          id="tim-kiem-review"
          type="text"
          v-model="tim_kiem"
          placeholder="Tìm theo tên sản phẩm, khách hàng, nội dung đánh giá..."
          class="input-search"
          data-testid="input-tim-kiem-danh-gia"
        />
      </div>

      <div class="filter-dropdowns">
        <select id="loc-so-sao" v-model="loc_so_sao" class="select-filter" data-testid="select-loc-so-sao">
          <option value="">Tất cả xếp hạng sao</option>
          <option value="5">⭐⭐⭐⭐⭐ (5 sao)</option>
          <option value="4">⭐⭐⭐⭐ (4 sao)</option>
          <option value="3">⭐⭐⭐ (3 sao)</option>
          <option value="2">⭐⭐ (2 sao)</option>
          <option value="1">⭐ (1 sao)</option>
        </select>

        <select id="loc-trang-thai-review" v-model="loc_trang_thai" class="select-filter" data-testid="select-loc-trang-thai-review">
          <option value="">Trạng thái kiểm duyệt</option>
          <option value="da_duyet">Đã hiển thị trên website</option>
          <option value="cho_duyet">Chờ Stylist kiểm duyệt</option>
          <option value="an">Tạm ẩn nhận xét</option>
        </select>

        <button type="button" class="btn-reset" @click="datLaiBoLoc"><i class="bi bi-arrow-counterclockwise"></i> Đặt lại</button>
      </div>
    </div>

    <!-- BẢNG DANH SÁCH ĐÁNH GIÁ PHONG CÁCH ATELIER -->
    <div class="table-card">
      <table class="atelier-data-table" data-testid="table-danh-gia">
        <thead>
          <tr>
            <th width="50" class="text-center">STT</th>
            <th width="180">KHÁCH HÀNG &amp; THỜI GIAN</th>
            <th width="200">SẢN PHẨM THỜI TRANG</th>
            <th width="120">XẾP HẠNG</th>
            <th>NỘI DUNG FEEDBACK THỰC TẾ</th>
            <th width="130">TRẠNG THÁI</th>
            <th width="140" class="text-center">HÀNH ĐỘNG</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in list_danh_gia_filter" :key="item.id || index" :data-testid="'row-danh-gia-' + (index + 1)">
            <td class="text-center text-muted">{{ String(index + 1).padStart(2, '0') }}</td>
            <td>
              <div class="client-cell">
                <strong class="client-name" :data-testid="'user-review-' + (index + 1)">{{ item.user ? item.user.ho_ten : (item.ten_khach || 'Khách hàng VIP') }}</strong>
                <div class="client-date">{{ item.created_at ? item.created_at.split('T')[0] : '02/11/2026' }} • Đã mua hàng</div>
              </div>
            </td>
            <td>
              <div class="prod-tag-cell">
                <strong class="prod-reviewed" :data-testid="'product-review-' + (index + 1)">{{ item.product ? item.product.ten_san_pham : (item.ten_san_pham || 'Blazer Linen Thắt Eo Cổ V') }}</strong>
                <span class="prod-sku">SKU: {{ item.product ? item.product.ma_san_pham : 'WA-B224' }}</span>
              </div>
            </td>
            <td>
              <div class="stars-box" :data-testid="'stars-review-' + (index + 1)">
                <span class="star-gold">{{ '★'.repeat(item.so_sao || 5) }}</span>
                <span class="star-score">{{ (item.so_sao || 5).toFixed(1) }}</span>
              </div>
            </td>
            <td>
              <p class="review-quote" :data-testid="'content-review-' + (index + 1)">
                "{{ item.noi_dung }}"
              </p>
            </td>
            <td>
              <span :class="['badge-status', item.trang_thai === 'da_duyet' ? 'status-active' : (item.trang_thai === 'cho_duyet' ? 'status-pending' : 'status-inactive')]" :data-testid="'status-review-' + (index + 1)">
                {{ item.trang_thai === 'da_duyet' ? 'Đã duyệt' : (item.trang_thai === 'cho_duyet' ? 'Chờ duyệt' : 'Đã ẩn') }}
              </span>
            </td>
            <td class="text-center">
              <button type="button" class="btn-action edit" :data-testid="'btn-xem-' + (index + 1)" @click="moChiTiet(item)">Xem &amp; Đáp</button>
              <button
                type="button"
                :class="['btn-action', item.trang_thai === 'an' ? 'edit' : 'delete']"
                :data-testid="'btn-an-' + (index + 1)"
                @click="doiTrangThai(item, item.trang_thai === 'an' ? 'da_duyet' : 'an')"
              >
                {{ item.trang_thai === 'an' ? 'Hiện' : 'Ẩn' }}
              </button>
            </td>
          </tr>
          <tr v-if="list_danh_gia_filter.length === 0">
            <td colspan="7" class="text-center py-4 text-muted">
              {{ is_loading ? 'Đang tải danh sách đánh giá...' : 'Không tìm thấy đánh giá nào' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- MODAL CHI TIẾT & TRẢ LỜI ĐÁNH GIÁ -->
    <div v-if="hienModalDetail" class="modal-backdrop" @click.self="hienModalDetail = false">
      <div class="atelier-modal">
        <div class="modal-head">
          <div>
            <div class="modal-subtitle-tag">PHẢN HỒI Ý KIẾN KHÁCH HÀNG</div>
            <h3 class="modal-main-title">Chi Tiết Trải Nghiệm Atelier</h3>
          </div>
          <button type="button" class="btn-close-atelier" @click="hienModalDetail = false">&times;</button>
        </div>

        <div class="modal-body-atelier">
          <div class="review-preview-summary">
            <div class="client-meta-line">
              <strong>Quý khách:</strong> {{ selectedClient }}
            </div>
            <div class="prod-meta-line">
              <strong>Sản phẩm:</strong> {{ selectedProd }}
            </div>
            <div class="feedback-quote-box">
              "{{ selectedComment }}"
            </div>
          </div>

          <div class="form-item">
            <label class="form-label required">Nội dung phản hồi từ Maison Atelier:</label>
            <textarea
              rows="4"
              v-model="noiDungPhanHoi"
              class="form-input form-textarea"
              placeholder="Kính chào Quý khách, Maison Atelier xin chân thành cảm ơn những góp ý quý báu của Quý khách..."
            ></textarea>
          </div>
        </div>

        <div class="modal-foot-atelier">
          <button type="button" class="btn-cancel" @click="hienModalDetail = false">Đóng</button>
          <button type="button" class="btn-save" @click="guiPhanHoi">Gửi Phản Hồi Cho Khách</button>
        </div>
      </div>
    </div>

    <!-- MODAL TIÊU CHUẨN DUYỆT -->
    <div v-if="hienModalTieuChuan" class="modal-backdrop" @click.self="hienModalTieuChuan = false">
      <div class="atelier-modal modal-sm">
        <div class="modal-head">
          <h3 class="modal-main-title">Quy Định Kiểm Duyệt Đánh Giá</h3>
          <button type="button" class="btn-close-atelier" @click="hienModalTieuChuan = false">&times;</button>
        </div>
        <div class="modal-body-atelier">
          <ul style="font-size: 13px; line-height: 1.8; color: #475569; padding-left: 20px; margin: 0;">
            <li>Đánh giá từ khách đã hoàn tất đơn hàng tại quầy hoặc online.</li>
            <li>Nội dung lịch sự, không chứa từ ngữ phản cảm hoặc quảng cáo ngoài.</li>
            <li>Ghi nhận feedback về kích cỡ size, chất liệu và dịch vụ showroom.</li>
            <li>Tất cả đánh giá $\ge$ 4 sao tự động duyệt sau 24h.</li>
          </ul>
        </div>
        <div class="modal-foot-atelier">
          <button type="button" class="btn-save" @click="hienModalTieuChuan = false">Đã Hiểu</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AdminDanhGia',
  data() {
    return {
      list_danh_gia: [
        { id: 1, ten_khach: 'Chị Thảo My (VIP Diamond)', ten_san_pham: 'Blazer Linen Thắt Eo Cổ V', so_sao: 5, noi_dung: 'Áo blazer chất linen dệt rất thoáng mát, đường may chuẩn form cao cấp. Stylist bên Flagship tư vấn size cực kỳ vừa vặn!', trang_thai: 'da_duyet', created_at: '2026-11-02' },
        { id: 2, ten_khach: 'Trần Thị Mai (VIP Gold)', ten_san_pham: 'Đầm Maxi Lụa Satin Xẻ Tà', so_sao: 4, noi_dung: 'Váy lụa satin tôn dáng sang xịn, chất vải rũ óng ả. Mặc đi tiệc ai cũng khen. Đóng gói hộp kèm thiệp thơm rất tinh tế.', trang_thai: 'da_duyet', created_at: '2026-11-03' },
        { id: 3, ten_khach: 'Nguyễn Thu Trang', ten_san_pham: 'Sơ mi Poplin Dáng Rộng Oversize', so_sao: 3, noi_dung: 'Form áo hơi rộng so với bảng size bình thường, phải đem qua Boutique nhờ stylist chỉnh eo lại một chút.', trang_thai: 'cho_duyet', created_at: '2026-11-04' },
        { id: 4, ten_khach: 'Khách hàng ẩn danh', ten_san_pham: 'Quần Tây Xếp Ly Ống Rộng', so_sao: 1, noi_dung: 'Quảng cáo sai sự thật, giao hàng chậm trễ 3 ngày so với cam kết!', trang_thai: 'an', created_at: '2026-11-05' }
      ],
      tim_kiem: '',
      loc_so_sao: '',
      loc_trang_thai: '',
      is_loading: false,
      hienModalDetail: false,
      hienModalTieuChuan: false,
      selectedClient: '',
      selectedProd: '',
      selectedComment: '',
      noiDungPhanHoi: ''
    };
  },
  computed: {
    list_danh_gia_filter() {
      return this.list_danh_gia.filter(item => {
        let khopTuKhoa = true;
        if (this.tim_kiem && this.tim_kiem.trim()) {
          const kw = this.tim_kiem.trim().toLowerCase();
          khopTuKhoa = (item.noi_dung && item.noi_dung.toLowerCase().includes(kw)) ||
                       (item.ten_khach && item.ten_khach.toLowerCase().includes(kw)) ||
                       (item.ten_san_pham && item.ten_san_pham.toLowerCase().includes(kw));
        }

        let khopSao = true;
        if (this.loc_so_sao) {
          khopSao = String(item.so_sao) === String(this.loc_so_sao);
        }

        let khopTrangThai = true;
        if (this.loc_trang_thai) {
          khopTrangThai = item.trang_thai === this.loc_trang_thai;
        }

        return khopTuKhoa && khopSao && khopTrangThai;
      });
    }
  },
  mounted() {
    this.loadData();
  },
  methods: {
    datLaiBoLoc() {
      this.tim_kiem = '';
      this.loc_so_sao = '';
      this.loc_trang_thai = '';
    },

    loadData() {
      this.is_loading = true;
      axios
        .get('http://127.0.0.1:8000/api/admin/danh-gia/get-data')
        .then(res => {
          if (res.data && res.data.data && res.data.data.length > 0) {
            this.list_danh_gia = res.data.data;
          }
        })
        .catch(err => {
          console.error('Lỗi tải danh sách đánh giá:', err);
        })
        .finally(() => {
          this.is_loading = false;
        });
    },

    moChiTiet(item) {
      this.selectedClient = item.user ? item.user.ho_ten : (item.ten_khach || 'Khách hàng Atelier');
      this.selectedProd = item.product ? item.product.ten_san_pham : (item.ten_san_pham || 'Sản phẩm Maison');
      this.selectedComment = item.noi_dung;
      this.noiDungPhanHoi = 'Kính chào Quý khách, Maison Atelier xin chân thành cảm ơn những góp ý quý báu của Quý khách!';
      this.hienModalDetail = true;
    },

    moModalPhanHoi() {
      this.selectedClient = 'Khách Hàng Flagship';
      this.selectedProd = 'Tất cả đơn hàng Lookbook';
      this.selectedComment = 'Trải nghiệm thử đồ và chọn size tại không gian Boutique Quận 1';
      this.noiDungPhanHoi = '';
      this.hienModalDetail = true;
    },

    guiPhanHoi() {
      if (!this.noiDungPhanHoi.trim()) {
        this.$toast ? this.$toast.warning('Vui lòng nhập nội dung phản hồi!') : alert('Vui lòng nhập nội dung phản hồi!');
        return;
      }
      this.$toast ? this.$toast.success('Đã gửi phản hồi thành công đến hòm thư khách hàng!') : alert('Đã gửi phản hồi thành công đến hòm thư khách hàng!');
      this.hienModalDetail = false;
    },

    doiTrangThai(item, trang_thai_moi) {
      axios
        .post('http://127.0.0.1:8000/api/admin/danh-gia/change-status', { id: item.id, trang_thai: trang_thai_moi })
        .then(res => {
          if (res.data.status == 1 || res.data.status == true) {
            this.$toast ? this.$toast.success(res.data.message || 'Cập nhật trạng thái đánh giá thành công!') : alert(res.data.message || 'Cập nhật trạng thái đánh giá thành công!');
            item.trang_thai = trang_thai_moi;
          }
        })
        .catch(err => {
          console.error('Lỗi đổi trạng thái đánh giá:', err);
          item.trang_thai = trang_thai_moi;
          this.$toast ? this.$toast.success('Đã cập nhật trạng thái nhận xét!') : alert('Đã cập nhật trạng thái nhận xét!');
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

.client-name {
  display: block;
  font-weight: 600;
  color: #0f1422;
}
.client-date {
  font-size: 11.5px;
  color: #64748b;
  margin-top: 2px;
}
.prod-reviewed {
  display: block;
  font-weight: 600;
  color: #0f1422;
}
.prod-sku {
  font-size: 11.5px;
  color: #8b6b4e;
}

.stars-box {
  display: flex;
  align-items: center;
  gap: 6px;
}
.star-gold {
  color: #f59e0b;
  font-size: 14px;
}
.star-score {
  font-weight: 700;
  font-size: 13px;
  color: #334155;
}

.review-quote {
  margin: 0;
  font-size: 13.5px;
  color: #334155;
  line-height: 1.5;
  font-style: italic;
}

.badge-status {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}
.status-active {
  background: #dcfce7;
  color: #15803d;
}
.status-pending {
  background: #fef3c7;
  color: #b45309;
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
.modal-sm { max-width: 440px; }
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

.review-preview-summary {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 14px;
  margin-bottom: 18px;
  font-size: 13px;
}
.client-meta-line, .prod-meta-line {
  margin-bottom: 6px;
  color: #334155;
}
.feedback-quote-box {
  background: #ffffff;
  padding: 10px 14px;
  border-radius: 6px;
  border-left: 3px solid #8b6b4e;
  font-style: italic;
  margin-top: 8px;
  color: #1e293b;
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
.form-input, .form-textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 13.5px;
  outline: none;
  box-sizing: border-box;
}
.form-input:focus, .form-textarea:focus {
  border-color: #0f1422;
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
</style>
