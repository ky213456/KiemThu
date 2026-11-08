<template>
  <div class="toast-container" data-testid="toast-container">
    <transition-group name="toast-slide">
      <div
        v-for="item in toastState.toasts"
        :key="item.id"
        :class="['toast-card', 'toast-' + item.type]"
        data-testid="toast-item"
      >
        <div class="toast-icon">
          <i v-if="item.type === 'success'" class="bi bi-check-circle-fill"></i>
          <i v-else-if="item.type === 'error'" class="bi bi-x-circle-fill"></i>
          <i v-else-if="item.type === 'warning'" class="bi bi-exclamation-triangle-fill"></i>
          <i v-else class="bi bi-info-circle-fill"></i>
        </div>

        <div class="toast-content">
          <div class="toast-title">{{ item.title }}</div>
          <div class="toast-message">{{ item.message }}</div>
        </div>

        <button
          type="button"
          class="toast-close"
          @click="toast.remove(item.id)"
          title="Đóng thông báo"
        >
          <i class="bi bi-x-lg"></i>
        </button>

        <div class="toast-progress">
          <div
            class="toast-progress-bar"
            :style="{ animationDuration: item.duration + 'ms' }"
          ></div>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { toastState, toast } from '@/utils/toast'
</script>

<style scoped>
.toast-container {
  position: fixed;
  top: 24px;
  right: 24px;
  z-index: 999999;
  display: flex;
  flex-direction: column;
  gap: 12px;
  pointer-events: none;
  max-width: 420px;
  width: calc(100vw - 48px);
}

.toast-card {
  pointer-events: auto;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 14px 16px;
  border-radius: 12px;
  background: #ffffff;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.16), 0 4px 12px rgba(15, 23, 42, 0.08);
  border-left: 5px solid #64748b;
  color: #1e293b;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Success */
.toast-success {
  border-left-color: #10b981;
  background: linear-gradient(to right, #f0fdf4, #ffffff 35%);
}
.toast-success .toast-icon {
  color: #10b981;
}
.toast-success .toast-progress-bar {
  background: #10b981;
}

/* Error */
.toast-error {
  border-left-color: #ef4444;
  background: linear-gradient(to right, #fef2f2, #ffffff 35%);
}
.toast-error .toast-icon {
  color: #ef4444;
}
.toast-error .toast-progress-bar {
  background: #ef4444;
}

/* Warning */
.toast-warning {
  border-left-color: #f59e0b;
  background: linear-gradient(to right, #fffbeb, #ffffff 35%);
}
.toast-warning .toast-icon {
  color: #f59e0b;
}
.toast-warning .toast-progress-bar {
  background: #f59e0b;
}

/* Info */
.toast-info {
  border-left-color: #0284c7;
  background: linear-gradient(to right, #f0f9ff, #ffffff 35%);
}
.toast-info .toast-icon {
  color: #0284c7;
}
.toast-info .toast-progress-bar {
  background: #0284c7;
}

.toast-icon {
  font-size: 22px;
  line-height: 1;
  margin-top: 1px;
  flex-shrink: 0;
}

.toast-content {
  flex: 1;
  min-width: 0;
}

.toast-title {
  font-weight: 700;
  font-size: 14px;
  line-height: 1.3;
  margin-bottom: 2px;
  color: #0f172a;
}

.toast-message {
  font-size: 13px;
  line-height: 1.45;
  color: #475569;
  word-break: break-word;
}

.toast-close {
  background: transparent;
  border: none;
  cursor: pointer;
  color: #94a3b8;
  font-size: 12px;
  padding: 2px 4px;
  border-radius: 4px;
  transition: all 0.15s ease;
  line-height: 1;
  flex-shrink: 0;
}

.toast-close:hover {
  color: #1e293b;
  background: rgba(148, 163, 184, 0.15);
}

.toast-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: rgba(226, 232, 240, 0.6);
}

.toast-progress-bar {
  height: 100%;
  width: 100%;
  transform-origin: left;
  animation: toast-timer linear forwards;
}

@keyframes toast-timer {
  from {
    transform: scaleX(1);
  }
  to {
    transform: scaleX(0);
  }
}

/* Transitions */
.toast-slide-enter-active {
  animation: slideInRight 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-slide-leave-active {
  animation: slideOutRight 0.25s ease forwards;
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(100px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideOutRight {
  from {
    opacity: 1;
    transform: translateX(0);
  }
  to {
    opacity: 0;
    transform: translateX(100px);
  }
}
</style>
