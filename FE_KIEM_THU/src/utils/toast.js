import { reactive } from 'vue'

export const toastState = reactive({
  toasts: []
})

let idCounter = 0

export const toast = {
  show(message, type = 'info', title = '', duration = 3500) {
    const id = ++idCounter
    const item = {
      id,
      message,
      type, // 'success' | 'error' | 'warning' | 'info'
      title: title || (
        type === 'success' ? 'Thành Công' :
        type === 'error' ? 'Thất Bại / Lỗi' :
        type === 'warning' ? 'Cảnh Báo' : 'Thông Báo'
      ),
      duration
    }
    toastState.toasts.push(item)

    if (duration > 0) {
      setTimeout(() => {
        toast.remove(id)
      }, duration)
    }
    return id
  },
  success(message, title = 'Thành Công') {
    return this.show(message, 'success', title)
  },
  error(message, title = 'Thất Bại') {
    return this.show(message, 'error', title)
  },
  warning(message, title = 'Cảnh Báo') {
    return this.show(message, 'warning', title)
  },
  info(message, title = 'Thông Báo') {
    return this.show(message, 'info', title)
  },
  remove(id) {
    const index = toastState.toasts.findIndex(t => t.id === id)
    if (index !== -1) {
      toastState.toasts.splice(index, 1)
    }
  }
}

if (typeof window !== 'undefined') {
  window.$toast = toast
}

export function useToast() {
  return toast
}

export default toast
