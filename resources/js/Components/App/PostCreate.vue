<script setup>
const emit = defineEmits(['createPost'])

const createPost = () => {
  emit('createPost')
}
</script>

<template>
  <div
    class="mb-3 rounded-md border bg-white px-2 py-3 shadow-sm dark:border-gray-700 dark:bg-gray-800"
  >
    <div
      class="min-h-[44px] w-full cursor-pointer resize-none rounded-lg border border-gray-300 bg-white p-3 text-gray-500 transition-all duration-200 hover:border-indigo-500 hover:bg-gray-50 hover:ring-2 hover:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:ring-indigo-400"
      @click="createPost"
    >
      What's on your mind?
    </div>
  </div>
</template>

<style scoped></style>
