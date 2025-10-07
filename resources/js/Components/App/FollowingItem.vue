<script setup>
defineProps({
  image: String,
  name: String,
})
</script>

<template>
  <div class="mb-3 flex cursor-pointer items-center gap-3 rounded p-3 hover:bg-gray-100">
    <img :src="image" alt="image" class="h-12 w-12 rounded-full" />
    <div>
      <h3 class="text-md font-semibold">{{ name }}</h3>
    </div>
  </div>
</template>

<style scoped></style>
