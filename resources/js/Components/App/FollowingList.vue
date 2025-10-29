<script setup>
import FollowingListItems from '@/Components/App/FollowingListItems.vue'

defineProps({
  followers: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <div
    class="flex max-h-screen flex-col overflow-hidden rounded-md border bg-white px-3 py-4 shadow-sm dark:border-gray-700 dark:bg-gray-800"
  >
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-200">Followers</h2>
    <FollowingListItems :followers />
  </div>
</template>

<style scoped></style>
