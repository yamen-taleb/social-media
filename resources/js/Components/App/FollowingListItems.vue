<script setup>
import FollowingItem from '@/Components/App/FollowingItem.vue'
import { InfiniteScroll } from '@inertiajs/vue3'

defineProps({
  followers: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <div class="scrollbar-thin flex-1 overflow-y-auto">
    <div class="text-center font-semibold text-gray-400 dark:text-gray-500" v-if="false">
      You don't have followers yet!
    </div>
    <div v-else class="space-y-2">
      <InfiniteScroll data="followers" preserve-url>
        <FollowingItem
          v-for="follower in followers.data"
          :key="follower.id"
          :name="follower.name"
          :image="follower.avatar"
          :username="follower.username"
        />
      </InfiniteScroll>
    </div>
  </div>
</template>
