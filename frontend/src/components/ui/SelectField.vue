<template>
  <div class="mb-4">
    <label v-if="label" :for="id" class="form-label text-unicampo fw-semibold">{{ label }}</label>

    <select
      :id="id"
      class="form-select custom-select"
      v-model="modelValueLocal"
      :disabled="disabled"
    >
      <option value="" disabled hidden>{{ placeholder }}</option>
      <option
        v-for="(option, index) in options"
        :key="index"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>

    <small v-if="helper" class="form-text text-muted">{{ helper }}</small>
    <div v-if="error" class="invalid-feedback d-block">{{ error }}</div>
  </div>
</template>

<script>
export default {
  name: 'SelectField',
  props: {
    modelValue: [String, Number],
    label: String,
    placeholder: {
      type: String,
      default: 'Selecione uma opção'
    },
    options: {
      type: Array,
      required: true
    },
    error: String,
    helper: String,
    id: {
      type: String,
      default: () => `select-${Math.random().toString(36).substr(2, 9)}`
    },
    disabled: {
      type: Boolean,
      default: false
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
.custom-select {
  border-radius: 8px;
  border: 1px solid #ced4da;
  padding: 0.5rem 0.75rem;
  transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.custom-select:focus {
  border-color: #0a3b25;
  box-shadow: 0 0 0 0.2rem rgba(10, 59, 37, 0.25);
}

.invalid-feedback {
  color: #dc3545;
}
</style>
