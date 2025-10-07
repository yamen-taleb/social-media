<script setup>
defineProps({
  image: String,
  name: String,
  description: String,
})
</script>

<template>
  <div class="mb-3 flex cursor-pointer items-center gap-3 rounded p-3 hover:bg-gray-100">
    <img :src="image" alt="image" class="h-16 w-16 rounded-full" />
    <div>
      <h3 class="text-lg font-semibold">{{ name }}</h3>
      <p class="text-sm text-gray-500 hover:text-gray-600">{{ description }}</p>
    </div>
  </div>
</template>

<style scoped></style>
