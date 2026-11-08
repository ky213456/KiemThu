<template>
  <div class="auth-container" data-testid="page-dang-nhap">
    <div class="auth-card">
      <div class="auth-header">
        <div class="brand-crest">
          <span class="crest-icon">✦</span>
          <span class="brand-title">MAISON ATELIER</span>
          <span class="crest-icon">✦</span>
        </div>
        <div class="brand-sub">BOUTIQUE OPERATIONS &amp; FLAGSHIP PORTAL</div>
        <h2 class="auth-title" data-testid="title-dang-nhap">Đăng Nhập Hệ Thống</h2>
        <p class="auth-subtitle">Cổng quản trị nội bộ Flagship Q1 và Khách hàng VIP</p>
      </div>

      <!-- Thông báo lỗi hoặc thành công nếu có -->
      <div v-if="thongBaoLoi" class="alert-box alert-error">
        <span><i class="bi bi-exclamation-circle-fill"></i> {{ thongBaoLoi }}</span>
      </div>
      <div v-if="thongBaoThanhCong" class="alert-box alert-success">
        <span><i class="bi bi-check-circle-fill"></i> {{ thongBaoThanhCong }}</span>
      </div>

      <form data-testid="form-dang-nhap" @submit.prevent="xuLyDangNhap">
        <div class="form-group">
          <label for="login-email" class="form-label required">
            <i class="bi bi-envelope-at"></i> Địa chỉ Email / Mã nhân sự:
          </label>
          <input
            id="login-email"
            type="email"
            v-model="email"
            placeholder="admin@shop.com hoặc stylist@atelier.vn"
            class="form-control"
            data-testid="input-email"
          />
        </div>

        <div class="form-group">
          <div class="label-row">
            <label for="login-password" class="form-label required">
              <i class="bi bi-lock"></i> Mật khẩu:
            </label>
            <a href="#" class="forgot-link" data-testid="link-quen-mat-khau" @click.prevent="$emit('chuyen-tab', 'quen_mat_khau')">Quên mật khẩu?</a>
          </div>
          <input
            id="login-password"
            type="password"
            v-model="password"
            placeholder="Nhập mật khẩu..."
            class="form-control"
            data-testid="input-password"
          />
        </div>

        <div class="form-options">
          <label class="checkbox-label">
            <input type="checkbox" v-model="ghiNho" data-testid="checkbox-ghi-nho" />
            <span>Ghi nhớ phiên đăng nhập trên thiết bị POS</span>
          </label>
        </div>

        <button
          type="button"
          class="btn-submit"
          data-testid="btn-dang-nhap"
          @click="xuLyDangNhap"
          :disabled="isLoading"
        >
          <span v-if="isLoading"><i class="bi bi-arrow-repeat spin"></i> Đang xử lý...</span>
          <span v-else><i class="bi bi-box-arrow-in-right"></i> Đăng Nhập Cổng Quản Trị</span>
        </button>
      </form>

      <!-- Khung tài khoản kiểm thử mẫu (CLICK VÀO ĐỂ TỰ ĐỘNG ĐIỀN) -->
      <div class="test-accounts" data-testid="box-test-accounts">
        <div class="test-header"><i class="bi bi-flask"></i> Tài Khoản Mẫu (Click để chọn nhanh):</div>
        <div class="account-pill clickable-pill" @click="chonTaiKhoan('admin@shop.com', 'password123')" title="Click để điền tài khoản này">
          <span><i class="bi bi-shield-check text-primary"></i> Admin Flagship:</span> <code>admin@shop.com</code>
        </div>
        <div class="account-pill clickable-pill" @click="chonTaiKhoan('customer@shop.com', 'password123')" title="Click để điền tài khoản này">
          <span><i class="bi bi-person-heart text-success"></i> Khách hàng VIP:</span> <code>customer@shop.com</code>
        </div>
        <div class="account-pill account-pill-danger clickable-pill" @click="chonTaiKhoan('blocked@shop.com', 'password123')" title="Click để test case tài khoản bị khóa">
          <span><i class="bi bi-slash-circle text-danger"></i> Tài khoản khóa:</span> <code>blocked@shop.com</code>
        </div>
      </div>

      <div class="auth-footer">
        Thành viên mới của Maison Atelier?
        <a href="#" class="switch-link" data-testid="link-sang-dang-ky" @click.prevent="$emit('chuyen-tab', 'dang_ky')">Tạo tài khoản VIP</a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { toast } from '@/utils/toast'

const emit = defineEmits(['chuyen-tab'])

const email = ref('')
const password = ref('')
const ghiNho = ref(true)
const thongBaoLoi = ref('')
const thongBaoThanhCong = ref('')
const isLoading = ref(false)

function chonTaiKhoan(mail, pass) {
  email.value = mail
  password.value = pass
  thongBaoLoi.value = ''
  thongBaoThanhCong.value = ''
  toast.info('Đã điền tài khoản: ' + mail, 'Điền Nhanh')
}

function xuLyDangNhap() {
  thongBaoLoi.value = ''
  thongBaoThanhCong.value = ''
  isLoading.value = true

  axios
    .post('http://127.0.0.1:8000/api/dang-nhap', {
      email: email.value,
      password: password.value
    })
    .then(res => {
      if (res.data.status === true || res.data.status == 1) {
        const msg = res.data.message || 'Đăng nhập thành công!'
        thongBaoThanhCong.value = msg
        toast.success(msg, 'Đăng Nhập Thành Công')
        
        if (res.data.token) {
          localStorage.setItem('auth_token', res.data.token)
        }
        if (res.data.user) {
          localStorage.setItem('auth_user', JSON.stringify(res.data.user))
        }
        setTimeout(() => {
          emit('chuyen-tab', 'tong_quan')
        }, 600)
      } else {
        const msg = res.data.message || 'Đăng nhập không thành công!'
        thongBaoLoi.value = msg
        toast.error(msg, 'Đăng Nhập Thất Bại')
      }
    })
    .catch(err => {
      console.error('Lỗi đăng nhập:', err)
      const msg = err.response?.data?.message || 'Có lỗi xảy ra khi kết nối máy chủ!'
      thongBaoLoi.value = msg
      toast.error(msg, 'Đăng Nhập Thất Bại')
    })
    .finally(() => {
      isLoading.value = false
    })
}
</script>

<style scoped>
.auth-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: radial-gradient(circle at 50% 0%, #1e263c 0%, #0f1422 100%);
  padding: 24px;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.auth-card {
  background: #ffffff;
  width: 100%;
  max-width: 440px;
  padding: 38px 36px;
  border-radius: 18px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
}

.auth-header {
  text-align: center;
  margin-bottom: 24px;
}
.brand-crest {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-bottom: 4px;
}
.crest-icon {
  color: #c5a880;
  font-size: 14px;
}
.brand-title {
  font-size: 15px;
  font-weight: 800;
  letter-spacing: 3px;
  color: #0f1422;
}
.brand-sub {
  font-size: 9.5px;
  letter-spacing: 1.5px;
  color: #8b6b4e;
  font-weight: 700;
  margin-bottom: 16px;
}
.auth-title {
  margin: 0;
  font-size: 22px;
  font-weight: 700;
  color: #0f1422;
}
.auth-subtitle {
  margin: 6px 0 0;
  font-size: 13px;
  color: #64748b;
}

.alert-box {
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 18px;
}
.alert-error {
  background: #fef2f2;
  border: 1px solid #fee2e2;
  color: #b91c1c;
}
.alert-success {
  background: #f0fdf4;
  border: 1px solid #dcfce7;
  color: #15803d;
}

.form-group {
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
.label-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.forgot-link {
  font-size: 12.5px;
  color: #8b6b4e;
  font-weight: 600;
  text-decoration: none;
}
.forgot-link:hover {
  text-decoration: underline;
}

.form-control {
  width: 100%;
  padding: 11px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 14px;
  outline: none;
  box-sizing: border-box;
  transition: all 0.2s;
}
.form-control:focus {
  border-color: #0f1422;
  box-shadow: 0 0 0 3px rgba(15, 20, 34, 0.1);
}

.form-options {
  margin-bottom: 20px;
}
.checkbox-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: #475569;
  cursor: pointer;
}

.btn-submit {
  width: 100%;
  padding: 13px;
  background: #0f1422;
  color: #ffffff;
  border: none;
  border-radius: 8px;
  font-size: 14.5px;
  font-weight: 700;
  letter-spacing: 0.3px;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(15, 20, 34, 0.25);
  transition: all 0.2s;
}
.btn-submit:hover {
  background: #232c42;
}

.test-accounts {
  margin-top: 22px;
  padding: 14px;
  background: #f8fafc;
  border: 1px dashed #cbd5e1;
  border-radius: 10px;
}
.test-header {
  font-size: 12px;
  font-weight: 700;
  color: #475569;
  margin-bottom: 8px;
}
.account-pill {
  font-size: 12px;
  color: #334155;
  margin-bottom: 6px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 6px 10px;
  border-radius: 6px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
}
.clickable-pill {
  cursor: pointer;
  transition: all 0.15s;
}
.clickable-pill:hover {
  border-color: #8b6b4e;
  background: #fefce8;
}
.account-pill-danger {
  color: #dc2626;
  border-color: #fee2e2;
  background: #fff5f5;
}
.account-pill-danger:hover {
  border-color: #ef4444;
  background: #fee2e2;
}
.account-pill code {
  background: #f1f5f9;
  padding: 2px 6px;
  border-radius: 4px;
  font-size: 11px;
}

.auth-footer {
  margin-top: 22px;
  text-align: center;
  font-size: 13px;
  color: #64748b;
}
.switch-link {
  color: #0f1422;
  font-weight: 700;
  text-decoration: none;
  margin-left: 4px;
}
.switch-link:hover {
  text-decoration: underline;
}
</style>
