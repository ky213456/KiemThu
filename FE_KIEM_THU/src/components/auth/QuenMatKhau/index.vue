<template>
  <div class="auth-container" data-testid="page-quen-mat-khau">
    <div class="auth-card">
      <div class="auth-header">
        <div class="brand-crest">
          <span class="crest-icon">✦</span>
          <span class="brand-title">MAISON ATELIER</span>
          <span class="crest-icon">✦</span>
        </div>
        <div class="brand-sub">KHÔI PHỤC QUYỀN TRUY CẬP</div>
        <h2 class="auth-title" data-testid="title-quen-mat-khau">Quên Mật Khẩu?</h2>
        <p class="auth-subtitle">Nhập email đăng ký để nhận mã OTP xác thực khôi phục mật khẩu</p>
      </div>

      <!-- Thông báo -->
      <div v-if="thongBaoLoi" class="alert-box alert-error">
        <span><i class="bi bi-exclamation-circle-fill"></i> {{ thongBaoLoi }}</span>
      </div>
      <div v-if="thongBaoThanhCong" class="alert-box alert-success">
        <span><i class="bi bi-check-circle-fill"></i> {{ thongBaoThanhCong }}</span>
      </div>

      <form data-testid="form-quen-mat-khau" @submit.prevent="xuLyQuenMatKhau">
        <div class="form-group">
          <label for="forgot-email" class="form-label required">
            <i class="bi bi-envelope-at"></i> Địa chỉ Email tài khoản:
          </label>
          <input
            id="forgot-email"
            type="email"
            v-model="email"
            placeholder="client@atelier.vn"
            class="form-control"
            data-testid="input-forgot-email"
          />
        </div>

        <button
          type="button"
          class="btn-submit"
          data-testid="btn-forgot-submit"
          @click="xuLyQuenMatKhau"
          :disabled="isLoading"
        >
          <span v-if="isLoading"><i class="bi bi-arrow-repeat spin"></i> Đang gửi...</span>
          <span v-else><i class="bi bi-send-fill"></i> Gửi Hướng Dẫn Khôi Phục</span>
        </button>
      </form>

      <div class="auth-footer">
        Đã nhớ lại mật khẩu?
        <a href="#" class="switch-link" data-testid="link-quay-lai-dang-nhap" @click.prevent="$emit('chuyen-tab', 'dang_nhap')">
          <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
        </a>
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
const thongBaoLoi = ref('')
const thongBaoThanhCong = ref('')
const isLoading = ref(false)

function xuLyQuenMatKhau() {
  thongBaoLoi.value = ''
  thongBaoThanhCong.value = ''
  isLoading.value = true

  axios
    .post('http://127.0.0.1:8000/api/quen-mat-khau', {
      email: email.value
    })
    .then(res => {
      if (res.data.status === true || res.data.status == 1) {
        const msg = res.data.message || 'Mã OTP khôi phục đã được gửi về email của bạn!'
        thongBaoThanhCong.value = msg
        toast.success(msg, 'Đã Gửi Hướng Dẫn')
      } else {
        const msg = res.data.message || 'Yêu cầu không thành công!'
        thongBaoLoi.value = msg
        toast.error(msg, 'Yêu Cầu Thất Bại')
      }
    })
    .catch(err => {
      console.error('Lỗi quên mật khẩu:', err)
      const msg = err.response?.data?.message || 'Có lỗi xảy ra khi gửi yêu cầu!'
      thongBaoLoi.value = msg
      toast.error(msg, 'Yêu Cầu Thất Bại')
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
  margin-bottom: 20px;
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
