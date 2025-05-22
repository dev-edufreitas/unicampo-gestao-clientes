<template>
  <div id="app" class="d-flex flex-column min-vh-100">
    <AppHeader />

    <main class="flex-grow-1 py-5">
      <div class=" app-wrapper container pt-5 mt-4 flex-grow-1">
        <router-view />
      </div>
    </main>

    <AppFooter />
  </div>
</template>

<script>
import AppHeader from '@/components/common/AppHeader.vue';
import AppFooter from '@/components/common/AppFooter.vue';

export default {
  name: 'App',
  components: {
    AppHeader,
    AppFooter
  }
};
</script>

<style scoped>
.app-wrapper {
  max-width: 1200px;
}
</style>
