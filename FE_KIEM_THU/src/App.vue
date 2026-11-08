<template>
  <div class="maison-app" data-testid="app-root">
    
    <!-- 1. VIEW AUTH: ĐĂNG NHẬP / ĐĂNG KÝ (NẾU CHỌN TAB AUTH) -->
    <div v-if="laTrangAuth" class="auth-fullscreen">
      <div v-if="daDangNhap" class="auth-top-switch">
        <button class="btn-back-atelier" data-testid="btn-ve-admin" @click="chuyenTab('tong_quan')">
          ← Quay lại Maison Atelier Dashboard
        </button>
      </div>

      <DangNhap
        v-if="tabHienTai === 'dang_nhap'"
        @chuyen-tab="chuyenTab"
      />
      <DangKy
        v-else-if="tabHienTai === 'dang_ky'"
        @chuyen-tab="chuyenTab"
      />
      <QuenMatKhau
        v-else-if="tabHienTai === 'quen_mat_khau'"
        @chuyen-tab="chuyenTab"
      />
    </div>

    <!-- 2. GIAO DIỆN CHÍNH BOUTIQUE FASHION (SIDEBAR + TOPBAR + CONTENT) -->
    <div v-else class="atelier-layout" data-testid="admin-layout">
      
      <!-- SIDEBAR XANH ĐEN SANG TRỌNG PHONG CÁCH BOUTIQUE ATELIER -->
      <aside class="atelier-sidebar" data-testid="admin-sidebar">
        <!-- Logo & Chi nhánh -->
        <div class="brand-section">
          <div class="brand-avatar">MA</div>
          <div class="brand-info">
            <h2 class="brand-title">MAISON ATELIER</h2>
            <div class="store-badge">
              <span class="dot-green"></span> Quận 1 - Flagship Boutique
            </div>
          </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="sidebar-menu">
          <!-- TỔNG QUAN -->
          <div class="menu-heading">TỔNG QUAN</div>
          <a
            href="#"
            :class="['menu-item', tabHienTai === 'tong_quan' ? 'active' : '']"
            data-testid="sidebar-tong-quan"
            @click.prevent="chuyenTab('tong_quan')"
          >
            <span class="menu-icon">📊</span>
            <span class="menu-text">Báo cáo doanh số</span>
            <span class="live-pill">LIVE</span>
          </a>

          <!-- QUẢN LÝ SẢN PHẨM & KHO -->
          <div class="menu-heading">QUẢN LÝ SẢN PHẨM & KHO</div>
          <a
            href="#"
            :class="['menu-item', tabHienTai === 'quan_ly_san_pham' ? 'active' : '']"
            data-testid="sidebar-san-pham"
            @click.prevent="chuyenTab('quan_ly_san_pham')"
          >
            <span class="menu-icon">👗</span>
            <span class="menu-text">Tất cả sản phẩm</span>
          </a>
          <a
            href="#"
            :class="['menu-item', tabHienTai === 'quan_ly_danh_muc' ? 'active' : '']"
            data-testid="sidebar-danh-muc"
            @click.prevent="chuyenTab('quan_ly_danh_muc')"
          >
            <span class="menu-icon">📑</span>
            <span class="menu-text">Bộ sưu tập / Lookbook</span>
          </a>
          <a
            href="#"
            :class="['menu-item', tabHienTai === 'quan_ly_san_pham' ? '' : '']"
            @click.prevent="chuyenTab('quan_ly_san_pham')"
          >
            <span class="menu-icon">🏷️</span>
            <span class="menu-text">Size & Màu sắc</span>
          </a>
          <a
            href="#"
            :class="['menu-item', tabHienTai === 'quan_ly_san_pham' ? '' : '']"
            @click.prevent="chuyenTab('quan_ly_san_pham')"
          >
            <span class="menu-icon">⚠️</span>
            <span class="menu-text">Tồn kho cảnh báo</span>
            <span class="dot-warning"></span>
          </a>

          <!-- BÁN HÀNG & ĐƠN HÀNG -->
          <div class="menu-heading">BÁN HÀNG & ĐƠN HÀNG</div>
          <a
            href="#"
            :class="['menu-item', tabHienTai === 'quan_ly_don_hang' ? 'active' : '']"
            data-testid="sidebar-don-hang"
            @click.prevent="chuyenTab('quan_ly_don_hang')"
          >
            <span class="menu-icon">🛍️</span>
            <span class="menu-text">Đơn tại quầy & Online</span>
          </a>
          <a href="#" class="menu-item" @click.prevent="chuyenTab('quan_ly_don_hang')">
            <span class="menu-icon">🔄</span>
            <span class="menu-text">Quản lý trả hàng</span>
          </a>
          <a href="#" class="menu-item" @click.prevent="chuyenTab('quan_ly_don_hang')">
            <span class="menu-icon">🚚</span>
            <span class="menu-text">Vận chuyển</span>
          </a>

          <!-- KHÁCH HÀNG & MARKETING -->
          <div class="menu-heading">KHÁCH HÀNG & MARKETING</div>
          <a
            href="#"
            :class="['menu-item', tabHienTai === 'quan_ly_nguoi_dung' ? 'active' : '']"
            data-testid="sidebar-nguoi-dung"
            @click.prevent="chuyenTab('quan_ly_nguoi_dung')"
          >
            <span class="menu-icon">💎</span>
            <span class="menu-text">Khách hàng VIP</span>
          </a>
          <a
            href="#"
            :class="['menu-item', tabHienTai === 'quan_ly_ma_giam_gia' ? 'active' : '']"
            data-testid="sidebar-voucher"
            @click.prevent="chuyenTab('quan_ly_ma_giam_gia')"
          >
            <span class="menu-icon">🎟️</span>
            <span class="menu-text">Voucher & Ưu đãi</span>
          </a>
          <a
            href="#"
            :class="['menu-item', tabHienTai === 'quan_ly_danh_gia' ? 'active' : '']"
            data-testid="sidebar-danh-gia"
            @click.prevent="chuyenTab('quan_ly_danh_gia')"
          >
            <span class="menu-icon">⭐</span>
            <span class="menu-text">Đánh giá Atelier</span>
          </a>

          <!-- MÀN HÌNH TEST AUTH -->
          <div class="menu-heading">MÀN HÌNH TEST XÁC THỰC</div>
          <a href="#" class="menu-item menu-item-test" @click.prevent="chuyenTab('dang_nhap')">
            <span class="menu-icon">🔑</span>
            <span class="menu-text">View Đăng Nhập</span>
          </a>
          <a href="#" class="menu-item menu-item-test" @click.prevent="chuyenTab('dang_ky')">
            <span class="menu-icon">📝</span>
            <span class="menu-text">View Đăng Ký</span>
          </a>
        </nav>

        <!-- CA LÀM VIỆC HIỆN TẠI DƯỚI CÙNG -->
        <div class="shift-footer" @click="moModalBanGiaoCa" style="cursor: pointer;" title="Click để xem chi tiết hoặc bàn giao ca">
          <div class="shift-status">
            <span class="shift-label">CA LÀM VIỆC HIỆN TẠI</span>
            <span class="shift-indicator"></span>
          </div>
          <div class="shift-name">Ca Sáng (08:30 - 15:30)</div>
          <div class="shift-cash">
            <span>Tiền mở két:</span>
            <strong>2.000.000đ</strong>
          </div>
        </div>
      </aside>

      <!-- KHU VỰC NỘI DUNG CHÍNH (TOPBAR + MAIN CONTENT) -->
      <div class="atelier-main">
        
        <!-- TOPBAR PHONG CÁCH BOUTIQUE -->
        <header class="atelier-topbar" data-testid="top-header">
          <!-- Thanh tìm kiếm rộng giữa màn hình -->
          <div class="topbar-search">
            <span class="search-icon">🔍</span>
            <input
              type="text"
              v-model="tuKhoaTimKiem"
              placeholder="Tìm nhanh sản phẩm (SKU, tên), đơn hàng (nhấn Enter)..."
              class="topbar-input"
              @keyup.enter="timKiemTopbar"
            />
          </div>

          <!-- Các nút hành động bên phải -->
          <div class="topbar-actions">
            <!-- Nút chọn nhanh view kiểm thử -->
            <div class="view-tester-switch">
              <span class="switch-tag">VIEW:</span>
              <select :value="tabHienTai" class="select-page" @change="chuyenTab($event.target.value)">
                <option value="tong_quan">📊 Báo cáo doanh số (Dashboard)</option>
                <option value="quan_ly_san_pham">👗 Tất cả sản phẩm</option>
                <option value="quan_ly_danh_muc">📑 Bộ sưu tập / Lookbook</option>
                <option value="quan_ly_don_hang">🛍️ Bán hàng & Đơn hàng</option>
                <option value="quan_ly_nguoi_dung">💎 Khách hàng VIP & Users</option>
                <option value="quan_ly_ma_giam_gia">🎟️ Voucher & Ưu đãi</option>
                <option value="quan_ly_danh_gia">⭐ Đánh giá Atelier</option>
                <option value="dang_nhap">🔑 View Đăng nhập</option>
                <option value="dang_ky">📝 View Đăng ký</option>
              </select>
            </div>

            <button type="button" class="btn-topbar" @click="hienModalChiNhanh = true">
              <span class="icon">🏬</span> {{ chiNhanhHienTai }} <span class="arrow">▾</span>
            </button>

            <button type="button" class="btn-topbar" @click="moModalBanGiaoCa">
              <span class="icon">🔄</span> Bàn giao ca
            </button>

            <div class="btn-notification" @click="hienPopupThongBao = !hienPopupThongBao" title="Thông báo hệ thống">
              <span class="bell-icon">🔔</span>
              <span class="bell-dot"></span>

              <!-- Dropdown thông báo -->
              <div v-if="hienPopupThongBao" class="notification-dropdown" @click.stop>
                <div class="noti-header">
                  <strong>Thông Báo Atelier</strong>
                  <span class="noti-badge">3 mới</span>
                </div>
                <div class="noti-item">
                  <span class="noti-icon">🛍️</span>
                  <div>
                    <div class="noti-text">Đơn hàng mới <strong>#ORD-5821</strong> vừa được đặt tại quầy Q1.</div>
                    <small class="noti-time">Vừa xong</small>
                  </div>
                </div>
                <div class="noti-item">
                  <span class="noti-icon">⚠️</span>
                  <div>
                    <div class="noti-text">Mẫu <strong>Blazer Linen Be</strong> sắp hết size S (còn 2 chiếc).</div>
                    <small class="noti-time">10 phút trước</small>
                  </div>
                </div>
                <div class="noti-item">
                  <span class="noti-icon">💎</span>
                  <div>
                    <div class="noti-text">Khách hàng VIP <strong>Chị Thảo My</strong> vừa ghé thăm showroom.</div>
                    <small class="noti-time">25 phút trước</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- Profile Người Dùng -->
            <div class="user-profile-widget" @click="hienMenuProfile = !hienMenuProfile">
              <div class="user-text">
                <span class="user-name">{{ tenHienThi }}</span>
                <span class="user-role">{{ vaiTroHienThi }}</span>
              </div>
              <img
                src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80"
                alt="Avatar"
                class="user-avatar"
              />

              <!-- Dropdown profile -->
              <div v-if="hienMenuProfile" class="profile-dropdown" @click.stop>
                <div class="profile-dropdown-item" @click="chuyenTab('quan_ly_nguoi_dung'); hienMenuProfile = false">
                  <span>👤</span> Hồ sơ nhân sự
                </div>
                <div class="profile-dropdown-item" @click="chuyenTab('tong_quan'); hienMenuProfile = false">
                  <span>📊</span> Báo cáo ca làm
                </div>
                <div class="profile-dropdown-item logout" @click="dangXuat">
                  <span>🚪</span> Đăng xuất hệ thống
                </div>
              </div>
            </div>
          </div>
        </header>

        <!-- VIEW CHÍNH ĐƯỢC HIỂN THỊ -->
        <main class="atelier-content">
          <TongQuan v-if="tabHienTai === 'tong_quan'" @chuyen-tab="chuyenTab" />
          <QuanLySanPham v-else-if="tabHienTai === 'quan_ly_san_pham'" />
          <QuanLyDanhMuc v-else-if="tabHienTai === 'quan_ly_danh_muc'" />
          <QuanLyDonHang v-else-if="tabHienTai === 'quan_ly_don_hang'" />
          <QuanLyNguoiDung v-else-if="tabHienTai === 'quan_ly_nguoi_dung'" />
          <QuanLyMaGiamGia v-else-if="tabHienTai === 'quan_ly_ma_giam_gia'" />
          <QuanLyDanhGia v-else-if="tabHienTai === 'quan_ly_danh_gia'" />
        </main>

      </div>
    </div>

    <!-- MODAL ĐỔI CHI NHÁNH -->
    <div v-if="hienModalChiNhanh" class="atelier-modal-backdrop" @click.self="hienModalChiNhanh = false">
      <div class="atelier-modal modal-sm">
        <div class="modal-head">
          <h3>Chọn Chi Nhánh Atelier</h3>
          <button type="button" class="btn-close" @click="hienModalChiNhanh = false">&times;</button>
        </div>
        <div class="modal-body">
          <div class="branch-list">
            <div
              v-for="cn in danhSachChiNhanh"
              :key="cn"
              :class="['branch-option', chiNhanhHienTai === cn ? 'active' : '']"
              @click="chonChiNhanh(cn)"
            >
              <span>🏬 {{ cn }}</span>
              <span v-if="chiNhanhHienTai === cn" class="branch-check">✓ Đang chọn</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL BÀN GIAO CA -->
    <div v-if="hienModalBanGiaoCa" class="atelier-modal-backdrop" @click.self="hienModalBanGiaoCa = false">
      <div class="atelier-modal modal-sm">
        <div class="modal-head">
          <h3>Bàn Giao Ca Làm Việc</h3>
          <button type="button" class="btn-close" @click="hienModalBanGiaoCa = false">&times;</button>
        </div>
        <div class="modal-body">
          <div class="shift-info-card">
            <div class="shift-row"><span>Nhân sự phụ trách:</span> <strong>Trần Mai Anh (Quản lý)</strong></div>
            <div class="shift-row"><span>Thời gian ca:</span> <strong>Ca Sáng (08:30 - 15:30)</strong></div>
            <div class="shift-row"><span>Tiền mở két đầu ca:</span> <strong>2.000.000đ</strong></div>
            <div class="shift-row"><span>Doanh thu thu tiền mặt:</span> <strong class="text-green">14.850.000đ</strong></div>
            <div class="shift-row highlight"><span>Tổng tiền bàn giao két:</span> <strong class="text-danger">16.850.000đ</strong></div>
          </div>
          <div class="form-group" style="margin-top: 14px;">
            <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Ghi chú bàn giao:</label>
            <input type="text" value="Đầy đủ hóa đơn và tiền mặt khớp két, đã kiểm đếm xong." class="topbar-input" style="border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px;" />
          </div>
        </div>
        <div class="modal-foot">
          <button type="button" class="btn-cancel" @click="hienModalBanGiaoCa = false">Hủy</button>
          <button type="button" class="btn-save" @click="xacNhanBanGiaoCa">Xác Nhận Bàn Giao</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Import components
import TongQuan from './components/admin/TongQuan/index.vue'
import QuanLySanPham from './components/admin/QuanLySanPham/index.vue'
import QuanLyDanhMuc from './components/admin/QuanLyDanhMuc/index.vue'
import QuanLyDonHang from './components/admin/QuanLyDonHang/index.vue'
import QuanLyNguoiDung from './components/admin/QuanLyNguoiDung/index.vue'
import QuanLyMaGiamGia from './components/admin/QuanLyMaGiamGia/index.vue'
import QuanLyDanhGia from './components/admin/QuanLyDanhGia/index.vue'

// Import components Auth
import DangNhap from './components/auth/DangNhap/index.vue'
import DangKy from './components/auth/DangKy/index.vue'
import QuenMatKhau from './components/auth/QuenMatKhau/index.vue'

// Quản lý xác thực và tab hiện tại
// Khởi tạo mặc định luôn vào Đăng Nhập trước để thực hiện đăng nhập
const daDangNhap = ref(!!localStorage.getItem('auth_token'))
const tabHienTai = ref('dang_nhap')
const tuKhoaTimKiem = ref('')

// Thông tin người dùng đăng nhập
const thongTinNguoiDung = computed(() => {
  try {
    const raw = localStorage.getItem('auth_user')
    return raw ? JSON.parse(raw) : null
  } catch (e) {
    return null
  }
})

const tenHienThi = computed(() => {
  return thongTinNguoiDung.value?.ho_ten || 'Trần Mai Anh'
})

const vaiTroHienThi = computed(() => {
  if (thongTinNguoiDung.value?.vai_tro === 'admin') return 'Quản trị viên (Admin)'
  if (thongTinNguoiDung.value?.vai_tro) return 'Thành viên (' + thongTinNguoiDung.value.vai_tro + ')'
  return 'Quản lý Cửa Hàng'
})

// Dropdowns & Modals state
const hienModalChiNhanh = ref(false)
const hienModalBanGiaoCa = ref(false)
const hienPopupThongBao = ref(false)
const hienMenuProfile = ref(false)

const chiNhanhHienTai = ref('Quận 1 - Flagship')
const danhSachChiNhanh = ref([
  'Quận 1 - Flagship Boutique',
  'Quận 3 - Concept Atelier Store',
  'Quận 7 - Crescent Mall Suite',
  'Hà Nội - Tràng Tiền Plaza'
])

function chonChiNhanh(ten) {
  chiNhanhHienTai.value = ten.split(' - ')[0] + ' - Flagship'
  hienModalChiNhanh.value = false
  alert('Đã chuyển quyền quản lý làm việc sang: ' + ten)
}

function moModalBanGiaoCa() {
  hienModalBanGiaoCa.value = true
}

function xacNhanBanGiaoCa() {
  alert('Bàn giao ca thành công! Đã gửi biên bản đối soát két tiền 16.850.000đ về bộ phận kế toán.')
  hienModalBanGiaoCa.value = false
}

function dangXuat() {
  hienMenuProfile.value = false
  localStorage.removeItem('auth_token')
  localStorage.removeItem('auth_user')
  daDangNhap.value = false
  alert('Đã đăng xuất khỏi hệ thống thành công!')
  tabHienTai.value = 'dang_nhap'
}

function timKiemTopbar() {
  if (!tuKhoaTimKiem.value.trim()) return
  alert(`Tìm kiếm nhanh từ khóa: "${tuKhoaTimKiem.value}" trên toàn bộ hệ thống Atelier!`)
}

const laTrangAuth = computed(() => {
  return ['dang_nhap', 'dang_ky', 'quen_mat_khau'].includes(tabHienTai.value)
})

function chuyenTab(tab) {
  const token = localStorage.getItem('auth_token')
  daDangNhap.value = !!token
  const isAuthTab = ['dang_nhap', 'dang_ky', 'quen_mat_khau'].includes(tab)

  // Nếu truy cập vào trang quản trị mà chưa đăng nhập thì chặn lại
  if (!isAuthTab && !token) {
    alert('Vui lòng đăng nhập tài khoản trước khi truy cập hệ thống quản trị!')
    tabHienTai.value = 'dang_nhap'
    return
  }

  tabHienTai.value = tab
}
</script>

<style scoped>
/* ========================================================
   MAISON ATELIER STYLESHEET (BOUTIQUE FASHION LUXURY THEME)
   ======================================================== */
.maison-app {
  min-height: 100vh;
  display: flex;
  background-color: #f6f7f9;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  color: #1e293b;
}

/* Auth Fullscreen wrapper */
.auth-fullscreen {
  width: 100%;
  min-height: 100vh;
  position: relative;
}
.auth-top-switch {
  position: fixed;
  top: 16px;
  left: 20px;
  z-index: 1000;
}
.btn-back-atelier {
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: #ffffff;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}
.btn-back-atelier:hover { background: #0f1422; }

/* Atelier Layout */
.atelier-layout {
  display: flex;
  width: 100%;
  min-height: 100vh;
}

/* ========================================================
   SIDEBAR (DARK NAVY / SLATE BLUE PHONG CÁCH MAISON)
   ======================================================== */
.atelier-sidebar {
  width: 250px;
  background: #0f1422;
  color: #94a3b8;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  position: sticky;
  top: 0;
  height: 100vh;
  border-right: 1px solid #1e2538;
}

.brand-section {
  padding: 20px 18px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid #1a2235;
}
.brand-avatar {
  width: 38px;
  height: 38px;
  background: #232c45;
  color: #ffffff;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 14px;
  letter-spacing: 1px;
}
.brand-info {
  display: flex;
  flex-direction: column;
}
.brand-title {
  margin: 0;
  font-size: 13.5px;
  font-weight: 800;
  letter-spacing: 0.8px;
  color: #ffffff;
}
.store-badge {
  font-size: 11px;
  color: #64748b;
  display: flex;
  align-items: center;
  gap: 5px;
  margin-top: 2px;
}
.dot-green {
  width: 6px;
  height: 6px;
  background: #10b981;
  border-radius: 50%;
}

/* Menu items */
.sidebar-menu {
  flex: 1;
  padding: 14px 10px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.sidebar-menu::-webkit-scrollbar { width: 3px; }
.sidebar-menu::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }

.menu-heading {
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.8px;
  color: #4b5563;
  padding: 12px 12px 5px;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 12px;
  border-radius: 6px;
  color: #94a3b8;
  text-decoration: none;
  font-size: 12.5px;
  font-weight: 500;
  transition: all 0.15s ease;
}
.menu-item:hover {
  background: #192033;
  color: #f1f5f9;
}
.menu-item.active {
  background: #1e263d;
  color: #ffffff;
  font-weight: 600;
}
.menu-icon {
  font-size: 15px;
  width: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.menu-text {
  flex: 1;
}

.live-pill {
  font-size: 9px;
  font-weight: 800;
  background: #f97316;
  color: #ffffff;
  padding: 2px 6px;
  border-radius: 10px;
}
.dot-warning {
  width: 6px;
  height: 6px;
  background: #f59e0b;
  border-radius: 50%;
}
.menu-item-test {
  border: 1px dashed rgba(255, 255, 255, 0.1);
  margin-top: 2px;
}

/* Ca làm việc footer */
.shift-footer {
  padding: 14px 16px;
  background: #0b0f1a;
  border-top: 1px solid #1a2235;
}
.shift-status {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 4px;
}
.shift-label {
  font-size: 9.5px;
  font-weight: 700;
  color: #64748b;
  letter-spacing: 0.6px;
}
.shift-indicator {
  width: 7px;
  height: 7px;
  background: #10b981;
  border-radius: 50%;
}
.shift-name {
  font-size: 11.5px;
  font-weight: 600;
  color: #e2e8f0;
}
.shift-cash {
  font-size: 11px;
  color: #94a3b8;
  display: flex;
  justify-content: space-between;
  margin-top: 3px;
}
.shift-cash strong {
  color: #ffffff;
}

/* ========================================================
   MAIN CONTENT & TOPBAR
   ======================================================== */
.atelier-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
}

/* Topbar */
.atelier-topbar {
  height: 56px;
  background: #ffffff;
  border-bottom: 1px solid #e5e7eb;
  padding: 0 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 40;
}

.topbar-search {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #f3f4f6;
  border-radius: 8px;
  padding: 6px 12px;
  width: 440px;
}
.search-icon { font-size: 13px; color: #9ca3af; }
.topbar-input {
  border: none;
  background: transparent;
  font-size: 12.5px;
  outline: none;
  width: 100%;
  color: #1f2937;
}

.topbar-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.view-tester-switch {
  display: flex;
  align-items: center;
  gap: 6px;
  background: #f9fafb;
  padding: 4px 10px;
  border-radius: 6px;
  border: 1px solid #e5e7eb;
}
.switch-tag {
  font-size: 10px;
  font-weight: 800;
  color: #4b5563;
}
.select-page {
  border: 1px solid #d1d5db;
  border-radius: 4px;
  padding: 3px 8px;
  font-size: 12px;
  font-weight: 600;
  color: #111827;
  outline: none;
  cursor: pointer;
  background: #ffffff;
}

.btn-topbar {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 500;
  color: #374151;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}
.btn-topbar:hover { background: #f9fafb; }
.btn-topbar .arrow { font-size: 10px; color: #9ca3af; }

.btn-notification {
  position: relative;
  cursor: pointer;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 15px;
}
.bell-dot {
  position: absolute;
  top: 5px;
  right: 6px;
  width: 6px;
  height: 6px;
  background: #ef4444;
  border-radius: 50%;
}

.user-profile-widget {
  display: flex;
  align-items: center;
  gap: 10px;
  padding-left: 8px;
  border-left: 1px solid #e5e7eb;
}
.user-text {
  text-align: right;
  display: flex;
  flex-direction: column;
}
.user-name {
  font-size: 12.5px;
  font-weight: 700;
  color: #111827;
}
.user-role {
  font-size: 10.5px;
  color: #6b7280;
}
.user-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  object-fit: cover;
}

.atelier-content {
  flex: 1;
  overflow-y: auto;
  background: #f6f7f9;
}

/* Notification & Profile Dropdowns */
.notification-dropdown {
  position: absolute;
  top: 42px;
  right: -20px;
  width: 320px;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  border: 1px solid #e2e8f0;
  z-index: 100;
  padding: 12px;
}
.noti-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 8px;
  border-bottom: 1px solid #f1f5f9;
  font-size: 13px;
}
.noti-badge {
  background: #fef2f2;
  color: #dc2626;
  font-size: 11px;
  font-weight: 700;
  padding: 2px 6px;
  border-radius: 10px;
}
.noti-item {
  display: flex;
  gap: 10px;
  padding: 8px 0;
  border-bottom: 1px solid #f8fafc;
  font-size: 12px;
  text-align: left;
}
.noti-item:last-child {
  border-bottom: none;
}
.noti-time {
  color: #94a3b8;
  font-size: 10.5px;
}

.profile-dropdown {
  position: absolute;
  top: 48px;
  right: 20px;
  width: 180px;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  border: 1px solid #e2e8f0;
  z-index: 100;
  padding: 6px 0;
}
.profile-dropdown-item {
  padding: 8px 16px;
  font-size: 13px;
  font-weight: 600;
  color: #334155;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
}
.profile-dropdown-item:hover {
  background: #f8fafc;
}
.profile-dropdown-item.logout {
  color: #dc2626;
  border-top: 1px solid #f1f5f9;
}

.branch-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.branch-option {
  padding: 12px 14px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 13.5px;
  font-weight: 600;
  color: #334155;
}
.branch-option:hover {
  border-color: #0f172a;
  background: #f8fafc;
}
.branch-option.active {
  border-color: #0f172a;
  background: #f1f5f9;
  color: #0f172a;
}
.branch-check {
  font-size: 12px;
  color: #16a34a;
}

.shift-info-card {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 14px;
}
.shift-row {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  margin-bottom: 8px;
  color: #64748b;
}
.shift-row.highlight {
  margin-top: 10px;
  padding-top: 8px;
  border-top: 1px dashed #cbd5e1;
  font-size: 14px;
  color: #0f172a;
}
.text-green { color: #16a34a; }
.text-danger { color: #dc2626; }

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
  max-width: 500px;
  overflow: hidden;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
.modal-sm { max-width: 440px; }
.modal-head {
  padding: 16px 20px;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.modal-head h3 {
  margin: 0;
  font-size: 17px;
  font-weight: 700;
  color: #0f172a;
}
.btn-close {
  background: none;
  border: none;
  font-size: 20px;
  color: #94a3b8;
  cursor: pointer;
}
.modal-body {
  padding: 20px;
}
.modal-foot {
  padding: 14px 20px;
  background: #fafbfc;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
.btn-cancel {
  background: #ffffff;
  border: 1px solid #cbd5e1;
  padding: 8px 16px;
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
  padding: 8px 18px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}
</style>
