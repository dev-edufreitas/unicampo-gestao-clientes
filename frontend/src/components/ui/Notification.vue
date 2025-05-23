<template>
  <transition name="fade">
    <div
      v-if="visible"
      :class="['alert', typeClass, 'alert-notification']"
      class="d-flex align-items-center"
      :style="style"
    >
      <i :class="iconClass + ' me-2'"></i>
      <span>{{ message }}</span>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Notification',
  props: {
    message: String,
    type: { type: String, default: 'success' },
    duration: { type: Number, default: 3000 }
  },
  data() {
    return {
      visible: false
    };
  },
  computed: {
    typeClass() {
      return this.type === 'error' ? 'alert-danger' : 'alert-success';
    },
    iconClass() {
      return this.type === 'error' ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle';
    },
    style() {
      return `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      `;
    }
  },
  watch: {
    message(newVal) {
      if (newVal) {
        this.visible = true;
        setTimeout(() => (this.visible = false), this.duration);
      }
    }
  }
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
