<script setup>
import GroupItem from '@/Components/App/GroupItem.vue'
import { ref } from 'vue'

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
    <GroupItem v-else v-for="group in groups" :key="group.id" :group />
  </div>
</template>

<style scoped></style>
