<script setup>
import { ref, onMounted, defineProps, defineEmits, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: '',
  },
  isActive: {
    type: Boolean,
    default: false,
  },
  autofocus: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue', 'click'])
const textarea = ref('')

const adjustHeight = () => {
  if (textarea.value) {
    textarea.value.style.height = 'auto'
    textarea.value.style.height = `${Math.min(textarea.value.scrollHeight, 200)}px`
  }
}

watch(
  () => props.modelValue,
  () => {
    setTimeout(() => {
      adjustHeight()
    }, 10)
  },
  { immediate: true }
)

const handleInput = (event) => {
  emit('update:modelValue', event.target.value)
  adjustHeight()
}

const handleClick = () => {
  emit('click')
}

onMounted(() => {
  adjustHeight()
  if (props.autofocus && textarea.value) {
    textarea.value.focus()
  }
})
</script>

<template>
  <textarea
    :autofocus="autofocus"
    :value="modelValue"
    ref="textarea"
    class="hide-scrollbar w-full resize-none rounded-lg border border-gray-300 p-3 text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:placeholder-gray-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-900"
    :class="isActive ? 'bg-white dark:bg-gray-700' : 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600'"
    rows="1"
    :placeholder="placeholder"
    @input="handleInput"
    @click="handleClick"
  >
  </textarea>
</template>
