<script setup>
import FollowingItem from '@/Components/App/FollowingItem.vue'
</script>

<template>
  <div class="scrollbar-thin flex-1 overflow-y-auto py-6">
    <div class="text-center font-semibold text-gray-400 dark:text-gray-500" v-if="false">
      You don't have friends yet!
    </div>
    <div v-else class="space-y-2">
      <FollowingItem image="https://picsum.photos/100" name="Laravel Developers" />
      <FollowingItem image="https://picsum.photos/100" name="Vue Developers" />
      <FollowingItem image="https://picsum.photos/100" name="Laravel Developers" />
      <FollowingItem image="https://picsum.photos/100" name="Vue Developers" />
    </div>
  </div>
</template>
