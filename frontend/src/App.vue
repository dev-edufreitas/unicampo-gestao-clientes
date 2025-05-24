<template>
  <div id="app" class="d-flex flex-column min-vh-100">
    <AppHeader />
    <Notification
      :message="notification.message"
      :type="notification.type"
      :duration="notification.duration"
      :key="notificationKey"
    />
    <main class="flex-grow-1 py-5">
      <div class="app-wrapper container pt-5 mt-4 flex-grow-1">
        <router-view />
      </div>
    </main>
    <AppFooter />
  </div>
</template>

<script>
import AppHeader from '@/components/common/AppHeader.vue';
import AppFooter from '@/components/common/AppFooter.vue';
import Notification from '@/components/ui/Notification.vue';
import eventBus from '@/utils/eventBus';

export default {
  name: 'App',
  components: {
    AppHeader,
    AppFooter,
    Notification
  },
  data() {
    return {
      notification: {
        message: '',
        type: 'success',
        duration: 3000
      },
      notificationKey: 0
    };
  },
  created() {
    eventBus.on('notify', ({ message, type = 'success', duration = 3000 }) => {
      this.notification = { message, type, duration };
      this.notificationKey++;
    });
  },
  beforeUnmount() {
    eventBus.off('notify');
  }
};
</script>

<style scoped>
.app-wrapper {
  max-width: 1200px;
}
</style>