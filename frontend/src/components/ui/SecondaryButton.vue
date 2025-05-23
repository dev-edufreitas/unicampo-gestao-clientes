<template>
  <router-link v-if="to" :to="to" :class="[
    'btn btn-unicampo-outline  d-flex align-items-center justify-content-center gap-2',
    block ? 'w-100' : ''
  ]">
    <i v-if="icon" :class="icon"></i>
    <slot />
  </router-link>

  <button v-else :type="type" :disabled="disabled" :class="[
    'btn btn-unicampo-outline d-flex align-items-center justify-content-center gap-2',
    block ? 'w-100' : ''
  ]" @click="$emit('click')">
    <i v-if="icon" :class="icon"></i>
    <slot />
  </button>
</template>

<script>
export default {
  name: 'SecondaryButton',
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
