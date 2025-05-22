<template>
  <div class="mb-4">
    <label v-if="label" :for="id" class="form-label text-unicampo fw-semibold">{{ label }}</label>

    <div class="input-group">
      <span v-if="prependIcon" class="input-group-text">
        <i :class="prependIcon" class="text-muted"></i>
      </span>

      <input
        :id="id"
        :type="type"
        class="form-control custom-input"
        :placeholder="placeholder"
        v-model="modelValueLocal"
        :disabled="disabled"
        :autocomplete="autocomplete"
      />
    </div>

    <small v-if="helper" class="form-text text-muted">{{ helper }}</small>
    <div v-if="error" class="invalid-feedback d-block">{{ error }}</div>
  </div>
</template>

<script>
export default {
  name: 'InputField',
  props: {
    modelValue: [String, Number],
    label: String,
    placeholder: String,
    error: String,
    helper: String,
    prependIcon: String,
    type: {
      type: String,
      default: 'text'
    },
    id: {
      type: String,
      default: () => `input-${Math.random().toString(36).substr(2, 9)}`
    },
    disabled: {
      type: Boolean,
      default: false
    },
    autocomplete: {
      type: String,
      default: 'off'
    }
  },
  emits: ['update:modelValue'],
  computed: {
    modelValueLocal: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit('update:modelValue', value);
      }
    }
  }
};
</script>

<style scoped>
.custom-input {
  border-radius: 8px;
  border: 1px solid #ced4da;
  padding: 0.5rem 0.75rem;
  transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.custom-input:focus {
  border-color: #0a3b25;
  box-shadow: 0 0 0 0.2rem rgba(10, 59, 37, 0.25);
}

.invalid-feedback {
  color: #dc3545;
}
</style>
