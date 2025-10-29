<script setup>
const props = defineProps({
  type: {
    type: String,
    default: 'button',
  },
  padding: {
    type: String,
    default: 'px-4 py-2',
  },
})
</script>

<template>
  <button
    :type="type"
    class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-indigo-800 disabled:opacity-50 dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
    :class="padding"
  >
    <slot />
  </button>
</template>
