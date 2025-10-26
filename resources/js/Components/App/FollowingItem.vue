<script setup>
defineProps({
  image: String,
  name: String,
})
</script>

<template>
  <div class="group mb-3 flex cursor-pointer items-center gap-3 rounded p-3 transition-colors hover:bg-gray-100 dark:hover:bg-gray-700">
    <img :src="image" alt="image" class="h-12 w-12 rounded-full border border-gray-200 dark:border-gray-600" />
    <div class="flex-1">
      <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ name }}</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">
        Online
      </p>
    </div>
  </div>
</template>

<style scoped></style>
