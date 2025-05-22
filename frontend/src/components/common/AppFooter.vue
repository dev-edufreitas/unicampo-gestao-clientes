<template>
  <footer class="app-footer mt-auto py-3 bg-light">
    <div class="container text-center">
      <span class="text-muted">Sistema de Gerenciamento de Clientes - UNICAMPO &copy; {{ currentYear }}</span>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter',
  computed: {
    currentYear() {
      return new Date().getFullYear();
    }
  }
};
</script>