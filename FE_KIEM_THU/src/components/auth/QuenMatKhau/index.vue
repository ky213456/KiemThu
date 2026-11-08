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
        <p class="auth-subtitle">
          <span v-if="buocHienTai === 1">Nhập email đăng ký để nhận mã OTP xác thực khôi phục mật khẩu</span>
          <span v-else>Nhập mã OTP 6 số và thiết lập mật khẩu bảo mật mới</span>
        </p>
      </div>

      <!-- Thanh tiến trình 2 bước -->
      <div class="steps-progress">
        <div :class="['step-item', buocHienTai >= 1 ? 'active' : '', buocHienTai > 1 ? 'completed' : '']">
          <span class="step-num">1</span>
          <span class="step-text">Gửi Mã OTP</span>
        </div>
        <div class="step-line" :class="{ 'active': buocHienTai >= 2 }"></div>
        <div :class="['step-item', buocHienTai === 2 ? 'active' : '']">
          <span class="step-num">2</span>
          <span class="step-text">Đặt Mật Khẩu</span>
        </div>
      </div>

      <!-- Thông báo hệ thống -->
      <div v-if="thongBaoLoi" class="alert-box alert-error">
        <span><i class="bi bi-exclamation-circle-fill"></i> {{ thongBaoLoi }}</span>
      </div>
      <div v-if="thongBaoThanhCong" class="alert-box alert-success">
        <span><i class="bi bi-check-circle-fill"></i> {{ thongBaoThanhCong }}</span>
      </div>

      <!-- ============================================== -->
      <!-- BƯỚC 1: NHẬP ĐỊA CHỈ EMAIL XÁC THỰC -->
      <!-- ============================================== -->
      <form v-if="buocHienTai === 1" data-testid="form-quen-mat-khau" @submit.prevent="xuLyGuiOTP">
        <div class="form-group">
          <label for="forgot-email" class="form-label required">
            <i class="bi bi-envelope-at"></i> Địa chỉ Email tài khoản:
          </label>
          <input
            id="forgot-email"
            type="email"
            v-model="email"
            placeholder="nguyenvanky20005@gmail.com"
            class="form-control"
            data-testid="input-forgot-email"
            required
          />
        </div>

        <!-- Box gợi ý chọn nhanh email chính chủ -->
        <div class="quick-mail-box" @click="email = 'nguyenvanky20005@gmail.com'" title="Click để chọn email này">
          <i class="bi bi-person-check-fill text-primary"></i>
          <span>Email thành viên: <strong>nguyenvanky20005@gmail.com</strong></span>
        </div>

        <button
          type="button"
          class="btn-submit"
          data-testid="btn-forgot-submit"
          @click="xuLyGuiOTP"
          :disabled="isLoading"
        >
          <span v-if="isLoading"><i class="bi bi-arrow-repeat spin"></i> Đang gửi mã...</span>
          <span v-else><i class="bi bi-send-fill"></i> Gửi Hướng Dẫn Khôi Phục</span>
        </button>
      </form>

      <!-- ============================================== -->
      <!-- BƯỚC 2: NHẬP MÃ OTP VÀ MẬT KHẨU MỚI -->
      <!-- ============================================== -->
      <form v-else-if="buocHienTai === 2" data-testid="form-dat-lai-mat-khau" @submit.prevent="xuLyDoiMatKhau">
        <!-- Badge thông tin email đã gửi -->
        <div class="email-sent-badge">
          <div class="sent-info">
            <i class="bi bi-mailbox2 text-success"></i>
            <span>Đã gửi mã OTP đến: <strong>{{ email }}</strong></span>
          </div>
          <button type="button" class="btn-change-email" @click="quayLaiBuoc1">
            <i class="bi bi-pencil-square"></i> Đổi
          </button>
        </div>


        <!-- Ô nhập OTP 6 số -->
        <div class="form-group">
          <label for="otp-code" class="form-label required">
            <i class="bi bi-shield-lock-fill"></i> Mã xác thực OTP (6 chữ số):
          </label>
          <input
            id="otp-code"
            type="text"
            v-model="maOtp"
            maxlength="6"
            placeholder="Ví dụ: 123456"
            class="form-control otp-input"
            data-testid="input-otp"
            required
          />
        </div>

        <!-- Mật khẩu mới -->
        <div class="form-group">
          <label for="new-password" class="form-label required">
            <i class="bi bi-lock-fill"></i> Mật khẩu bảo mật mới:
          </label>
          <input
            id="new-password"
            type="password"
            v-model="matKhauMoi"
            placeholder="Tối thiểu 6 ký tự..."
            class="form-control"
            data-testid="input-new-password"
            required
          />
        </div>

        <!-- Xác nhận mật khẩu mới -->
        <div class="form-group">
          <label for="confirm-new-password" class="form-label required">
            <i class="bi bi-check2-circle"></i> Xác nhận mật khẩu mới:
          </label>
          <input
            id="confirm-new-password"
            type="password"
            v-model="xacNhanMatKhauMoi"
            placeholder="Nhập lại mật khẩu mới..."
            class="form-control"
            data-testid="input-confirm-new-password"
            required
          />
        </div>

        <button
          type="button"
          class="btn-submit btn-submit-success"
          data-testid="btn-confirm-reset"
          @click="xuLyDoiMatKhau"
          :disabled="isLoading"
        >
          <span v-if="isLoading"><i class="bi bi-arrow-repeat spin"></i> Đang cập nhật mật khẩu...</span>
          <span v-else><i class="bi bi-shield-check"></i> Hoàn Tất Đổi Mật Khẩu</span>
        </button>

        <div class="resend-section">
          <span>Chưa nhận được mã? </span>
          <a href="#" class="resend-link" @click.prevent="xuLyGuiOTP">Gửi lại mã OTP</a>
        </div>
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

const buocHienTai = ref(1) // 1: Nhập email, 2: Nhập OTP & Đổi mật khẩu
const email = ref('nguyenvanky20005@gmail.com')
const maOtp = ref('')
const matKhauMoi = ref('')
const xacNhanMatKhauMoi = ref('')

const thongBaoLoi = ref('')
const thongBaoThanhCong = ref('')
const isLoading = ref(false)

function quayLaiBuoc1() {
  buocHienTai.value = 1
  thongBaoLoi.value = ''
  thongBaoThanhCong.value = ''
  maOtp.value = ''
  matKhauMoi.value = ''
  xacNhanMatKhauMoi.value = ''
}

// BƯỚC 1: GỬI MÃ OTP VỀ EMAIL
function xuLyGuiOTP() {
  thongBaoLoi.value = ''
  thongBaoThanhCong.value = ''

  if (!email.value || !email.value.trim()) {
    const msg = 'Vui lòng nhập địa chỉ email tài khoản!'
    thongBaoLoi.value = msg
    toast.warning(msg, 'Quên Mật Khẩu')
    return
  }

  isLoading.value = true

  axios
    .post('http://127.0.0.1:8000/api/quen-mat-khau', {
      email: email.value.trim()
    })
    .then(res => {
      if (res.data.status === true || res.data.status == 1) {
        const msg = res.data.message || 'Mã OTP xác thực đã được gửi về email của bạn!'
        thongBaoThanhCong.value = msg
        toast.success(msg, 'Đã Gửi Hướng Dẫn')

        // Chuyển sang Bước 2: Nhập OTP từ email và Đổi mật khẩu
        buocHienTai.value = 2
      } else {
        const msg = res.data.message || 'Yêu cầu không thành công!'
        thongBaoLoi.value = msg
        toast.error(msg, 'Yêu Cầu Thất Bại')
      }
    })
    .catch(err => {
      console.error('Lỗi quên mật khẩu:', err)
      const msg = err.response?.data?.message || 'Có lỗi xảy ra khi gửi yêu cầu khôi phục!'
      thongBaoLoi.value = msg
      toast.error(msg, 'Yêu Cầu Thất Bại')
    })
    .finally(() => {
      isLoading.value = false
    })
}

// BƯỚC 2: XÁC THỰC MÃ OTP VÀ THAY ĐỔI MẬT KHẨU
function xuLyDoiMatKhau() {
  thongBaoLoi.value = ''
  thongBaoThanhCong.value = ''

  if (!maOtp.value || !maOtp.value.trim()) {
    const msg = 'Vui lòng nhập mã OTP 6 chữ số!'
    thongBaoLoi.value = msg
    toast.warning(msg, 'Xác Thực OTP')
    return
  }

  if (maOtp.value.trim().length !== 6) {
    const msg = 'Mã OTP phải gồm đúng 6 chữ số!'
    thongBaoLoi.value = msg
    toast.warning(msg, 'Xác Thực OTP')
    return
  }

  if (!matKhauMoi.value || matKhauMoi.value.length < 6) {
    const msg = 'Mật khẩu mới phải có tối thiểu 6 ký tự!'
    thongBaoLoi.value = msg
    toast.warning(msg, 'Đặt Lại Mật Khẩu')
    return
  }

  if (matKhauMoi.value !== xacNhanMatKhauMoi.value) {
    const msg = 'Mật khẩu xác nhận không trùng khớp với mật khẩu mới!'
    thongBaoLoi.value = msg
    toast.warning(msg, 'Xác Nhận Mật Khẩu')
    return
  }

  isLoading.value = true

  axios
    .post('http://127.0.0.1:8000/api/dat-lai-mat-khau', {
      email: email.value.trim(),
      otp: maOtp.value.trim(),
      password: matKhauMoi.value,
      password_confirmation: xacNhanMatKhauMoi.value
    })
    .then(res => {
      if (res.data.status === true || res.data.status == 1) {
        const msg = res.data.message || 'Đổi mật khẩu thành công!'
        thongBaoThanhCong.value = msg
        toast.success(msg, 'Thành Công')

        // Tự động chuyển hướng về màn hình đăng nhập sau 1.5 giây
        setTimeout(() => {
          emit('chuyen-tab', 'dang_nhap')
        }, 1500)
      } else {
        const msg = res.data.message || 'Đổi mật khẩu thất bại!'
        thongBaoLoi.value = msg
        toast.error(msg, 'Lỗi Đổi Mật Khẩu')
      }
    })
    .catch(err => {
      console.error('Lỗi đổi mật khẩu:', err)
      const msg = err.response?.data?.message || 'Có lỗi xảy ra khi đặt lại mật khẩu!'
      thongBaoLoi.value = msg
      toast.error(msg, 'Lỗi Đổi Mật Khẩu')
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
  padding: 36px 34px;
  border-radius: 18px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
}

.auth-header {
  text-align: center;
  margin-bottom: 20px;
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
  margin-bottom: 14px;
}
.auth-title {
  margin: 0;
  font-size: 22px;
  font-weight: 700;
  color: #0f1422;
}
.auth-subtitle {
  margin: 6px 0 0;
  font-size: 12.5px;
  color: #64748b;
  line-height: 1.4;
}

/* Steps progress indicator */
.steps-progress {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 22px;
  padding: 8px 16px;
  background: #f8fafc;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
}
.step-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11.5px;
  font-weight: 600;
  color: #94a3b8;
}
.step-item.active {
  color: #0f1422;
  font-weight: 700;
}
.step-num {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #e2e8f0;
  color: #64748b;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
}
.step-item.active .step-num {
  background: #0f1422;
  color: #ffffff;
}
.step-item.completed .step-num {
  background: #10b981;
  color: #ffffff;
}
.step-line {
  flex: 1;
  height: 2px;
  background: #e2e8f0;
  margin: 0 12px;
}
.step-line.active {
  background: #0f1422;
}

.alert-box {
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 12.5px;
  font-weight: 600;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
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

.quick-mail-box {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #eff6ff;
  border: 1px dashed #bfdbfe;
  padding: 8px 12px;
  border-radius: 8px;
  margin-bottom: 18px;
  cursor: pointer;
  font-size: 12px;
  color: #1e40af;
  transition: all 0.2s ease;
}
.quick-mail-box:hover {
  background: #dbeafe;
  border-color: #93c5fd;
}

.email-sent-badge {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  padding: 8px 12px;
  border-radius: 8px;
  margin-bottom: 14px;
  font-size: 12px;
  color: #166534;
}
.sent-info {
  display: flex;
  align-items: center;
  gap: 6px;
}
.btn-change-email {
  background: none;
  border: none;
  color: #0284c7;
  font-size: 11.5px;
  font-weight: 700;
  cursor: pointer;
  padding: 2px 6px;
}
.btn-change-email:hover {
  text-decoration: underline;
}

.otp-hint-box {
  background: #fffbeb;
  border: 1px dashed #fde68a;
  border-radius: 8px;
  padding: 10px 14px;
  margin-bottom: 16px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s ease;
}
.otp-hint-box:hover {
  background: #fef3c7;
}
.otp-hint-title {
  font-size: 11.5px;
  font-weight: 600;
  color: #92400e;
  margin-bottom: 4px;
}
.otp-hint-code {
  font-size: 24px;
  font-weight: 800;
  letter-spacing: 6px;
  color: #78350f;
  font-family: monospace;
}

.form-group {
  margin-bottom: 16px;
}
.form-label {
  display: block;
  font-size: 12.5px;
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

.otp-input {
  font-size: 18px;
  font-weight: 700;
  letter-spacing: 4px;
  text-align: center;
}

.btn-submit {
  width: 100%;
  padding: 12px;
  background: #0f1422;
  color: #ffffff;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(15, 20, 34, 0.25);
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.btn-submit:hover {
  background: #232c42;
  transform: translateY(-1px);
}
.btn-submit-success {
  background: #059669;
}
.btn-submit-success:hover {
  background: #047857;
}

.resend-section {
  text-align: center;
  margin-top: 14px;
  font-size: 12px;
  color: #64748b;
}
.resend-link {
  color: #0284c7;
  font-weight: 700;
  text-decoration: none;
}
.resend-link:hover {
  text-decoration: underline;
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

.spin {
  display: inline-block;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
</style>
