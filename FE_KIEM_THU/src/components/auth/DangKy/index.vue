<template>
  <div class="auth-container" data-testid="page-dang-ky">
    <div class="auth-card">
      <div class="auth-header">
        <div class="brand-crest">
          <span class="crest-icon">✦</span>
          <span class="brand-title">MAISON ATELIER</span>
          <span class="crest-icon">✦</span>
        </div>
        <div class="brand-sub">BOUTIQUE OPERATIONS &amp; FLAGSHIP PORTAL</div>
        <h2 class="auth-title" data-testid="title-dang-ky">Đăng Ký Thành Viên VIP</h2>
        <p class="auth-subtitle">Nhận ngay quyền lợi đặc quyền và mã quà tặng gia nhập</p>
      </div>

      <!-- Thông báo lỗi hoặc thành công -->
      <div v-if="thongBaoLoi" class="alert-box alert-error">
        <span>⚠️ {{ thongBaoLoi }}</span>
      </div>
      <div v-if="thongBaoThanhCong" class="alert-box alert-success">
        <span>✅ {{ thongBaoThanhCong }}</span>
      </div>

      <form data-testid="form-dang-ky" @submit.prevent="xuLyDangKy">
        <div class="form-group">
          <label for="reg-hoten" class="form-label required">Họ và tên Quý khách:</label>
          <input
            id="reg-hoten"
            type="text"
            v-model="hoTen"
            placeholder="Ví dụ: Nguyễn Văn An"
            class="form-control"
            data-testid="input-register-hoten"
          />
        </div>

        <div class="form-group">
          <label for="reg-email" class="form-label required">Địa chỉ Email:</label>
          <input
            id="reg-email"
            type="email"
            v-model="email"
            placeholder="an.nguyen@example.com"
            class="form-control"
            data-testid="input-register-email"
          />
        </div>

        <div class="form-group">
          <label for="reg-phone" class="form-label required">Số điện thoại liên lạc:</label>
          <input
            id="reg-phone"
            type="tel"
            v-model="phone"
            placeholder="0912345678"
            class="form-control"
            data-testid="input-register-phone"
          />
        </div>

        <div class="form-group">
          <label for="reg-password" class="form-label required">Mật khẩu bảo mật:</label>
          <input
            id="reg-password"
            type="password"
            v-model="password"
            placeholder="Tối thiểu 6 ký tự..."
            class="form-control"
            data-testid="input-register-password"
          />
        </div>

        <div class="form-group">
          <label for="reg-confirm-password" class="form-label required">Xác nhận mật khẩu:</label>
          <input
            id="reg-confirm-password"
            type="password"
            v-model="confirmPassword"
            placeholder="Nhập lại mật khẩu..."
            class="form-control"
            data-testid="input-register-confirm-password"
          />
        </div>

        <div class="form-options">
          <label class="checkbox-label">
            <input type="checkbox" v-model="dongYDieuKhoan" data-testid="checkbox-dieukhoan" />
            <span>Tôi đồng ý với điều khoản thành viên Maison Atelier Club</span>
          </label>
        </div>

        <button
          type="button"
          class="btn-submit"
          data-testid="btn-dang-ky"
          @click="xuLyDangKy"
        >
          Hoàn Tất Đăng Ký Tài Khoản
        </button>
      </form>

      <div class="auth-footer">
        Đã có thẻ thành viên?
        <a href="#" class="switch-link" data-testid="link-sang-dang-nhap" @click.prevent="$emit('chuyen-tab', 'dang_nhap')">Đăng nhập tại đây</a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const emit = defineEmits(['chuyen-tab'])

const hoTen = ref('')
const email = ref('')
const phone = ref('')
const password = ref('')
const confirmPassword = ref('')
const dongYDieuKhoan = ref(false)

const thongBaoLoi = ref('')
const thongBaoThanhCong = ref('')
const isLoading = ref(false)

function xuLyDangKy() {
  thongBaoLoi.value = ''
  thongBaoThanhCong.value = ''
  isLoading.value = true

  axios
    .post('http://127.0.0.1:8000/api/dang-ky', {
      ho_ten: hoTen.value,
      email: email.value,
      so_dien_thoai: phone.value,
      mat_khau: password.value,
      re_mat_khau: confirmPassword.value,
      dong_y_dieu_khoan: dongYDieuKhoan.value
    })
    .then(res => {
      if (res.data.status === true || res.data.status == 1) {
        thongBaoThanhCong.value = res.data.message || 'Chúc mừng Quý khách! Đăng ký tài khoản VIP thành công.'
        setTimeout(() => {
          emit('chuyen-tab', 'dang_nhap')
        }, 1200)
      } else {
        thongBaoLoi.value = res.data.message || 'Đăng ký không thành công!'
      }
    })
    .catch(err => {
      console.error('Lỗi đăng ký:', err)
      const msg = err.response?.data?.message || 'Có lỗi xảy ra khi đăng ký tài khoản!'
      thongBaoLoi.value = msg
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
  max-width: 460px;
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
  margin-bottom: 14px;
}
.form-label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #334155;
  margin-bottom: 5px;
}
.required::after {
  content: " *";
  color: #ef4444;
}

.form-control {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 13.5px;
  outline: none;
  box-sizing: border-box;
  transition: all 0.2s;
}
.form-control:focus {
  border-color: #0f1422;
  box-shadow: 0 0 0 3px rgba(15, 20, 34, 0.1);
}

.form-options {
  margin: 16px 0 20px;
}
.checkbox-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12.5px;
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
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(15, 20, 34, 0.25);
  transition: all 0.2s;
}
.btn-submit:hover {
  background: #232c42;
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
