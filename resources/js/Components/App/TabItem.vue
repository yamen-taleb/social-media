<script setup>
defineProps({
  selected: Boolean,
  text: String,
})
</script>

<template>
  <button
    :class="[
      'focus:ring-none px-3 py-2.5 text-sm outline-none focus:outline-none',
      selected ? 'border-b-2 border-blue-500 bg-white text-blue-500' : 'text-gray-700',
    ]"
  >
    {{ text }}
  </button>
</template>

<style scoped></style>
