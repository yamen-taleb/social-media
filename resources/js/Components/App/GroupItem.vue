<script setup>
defineProps({
  group: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <div class="mb-3 flex cursor-pointer items-center gap-3 rounded p-3 hover:bg-gray-100">
    <img :src="group.thumbnail" alt="image" class="h-12 w-12 rounded-full" />
    <div>
      <h3 class="text-md font-semibold">{{ group.name }}</h3>
      <p class="text-xs text-gray-500 hover:text-gray-600">{{ group.description }}</p>
    </div>
  </div>
</template>

<style scoped></style>
