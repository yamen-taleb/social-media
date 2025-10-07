<script setup>
import FollowingListItems from '@/Components/App/FollowingListItems.vue'
</script>

<template>
  <div
    class="flex max-h-screen flex-col overflow-hidden rounded-md border bg-white px-3 py-4 shadow-sm"
  >
    <h2 class="mb-2 text-2xl font-semibold">Friends</h2>
    <FollowingListItems />
  </div>
</template>

<style scoped></style>
