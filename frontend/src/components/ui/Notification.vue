<template>
  <transition name="fade">
    <div v-if="visible" :class="['alert', typeClass, 'alert-notification']" class="d-flex align-items-center"
      :style="style">
      <i :class="iconClass + ' me-2'"></i>
      <span>{{ currentMessage }}</span>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Notification',
  props: {
    message : String,
    type    : {type: String, default: 'success'},
    duration: {type: Number, default: 3000}
  },
  data() {
    return {
      visible: false,
      currentMessage: '',
      timeout: null
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
    message: {
      handler(newVal) {
        if (newVal && newVal.trim()) {
          this.showNotification(newVal);
        }
      },
      immediate: true
    }
  },
  methods: {
    showNotification(message) {
    
      if (this.timeout) {
        clearTimeout(this.timeout);
      }
      
      this.currentMessage = message;
      this.visible        = true;

      this.timeout = setTimeout(() => {
        this.visible = false;
        this.currentMessage = '';
      }, this.duration);
    },

    hide() {
      this.visible = false;
      if (this.timeout) {
        clearTimeout(this.timeout);
      }
    }
  },
  beforeUnmount() {
    if (this.timeout) {
      clearTimeout(this.timeout);
    }
  }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}

.fade-enter-from {
  opacity: 0;
  transform: translateX(100px);
}

.fade-leave-to {
  opacity: 0;
  transform: translateX(100px);
}

.alert-notification {
  cursor: pointer;
}

.alert-notification:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}
</style>