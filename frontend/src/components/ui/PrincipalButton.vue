<template>
  <router-link v-if="to" :to="to" :class="[
    'btn btn-principal d-flex align-items-center justify-content-center gap-2',
    block ? 'w-100' : ''
  ]">
    <i v-if="icon" :class="icon"></i>
    <slot />
  </router-link>

  <button v-else :type="type" :disabled="disabled" :class="[
    'btn btn-principal d-flex align-items-center justify-content-center gap-2',
    block ? 'w-100' : ''
  ]" @click="$emit('click')">
    <i v-if="icon" :class="icon"></i>
    <slot />
  </button>
</template>

<script>
export default {
  name: 'PrincipalButton',
  props: {
    type: {
      type   : String,
      default: 'button'
    },
    icon    : String,
    block   : Boolean,
    disabled: Boolean,
    to      : String
  },
  emits: ['click']
};
</script>

<style scoped>
.btn-principal {
  background-color: #0a3b25;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  padding: 0.375rem 0.75rem;
  transition: all 0.2s ease-in-out;
  text-decoration: none;
}

.btn-principal:hover {
  background-color: #083d26;
  color: white;
  text-decoration: none;
}
</style>