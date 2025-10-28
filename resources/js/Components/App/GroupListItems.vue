<script setup>
import GroupItem from '@/Components/App/GroupItem.vue'
import { ref } from 'vue'
import { InfiniteScroll } from '@inertiajs/vue3'

defineProps({
  groups: {
    type: Array,
    required: true,
  },
})

const searchKeyword = ref('')
</script>

<template>
  <div class="scrollbar-thin flex-1 overflow-y-auto py-6">
    <div v-if="false" class="text-center font-semibold text-gray-400 dark:text-gray-500">
      You are not joining any groups.
    </div>
    <InfiniteScroll data="groups" v-else preserve-url>
      <GroupItem v-for="group in groups.data" :key="group.id" :group />
    </InfiniteScroll>
  </div>
</template>

<style scoped></style>
