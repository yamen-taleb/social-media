<script setup>
import GroupListItems from '@/Components/App/GroupListItems.vue'
import { provide, ref } from 'vue'
import GroupCreateModel from '@/Components/App/GroupCreateModel.vue'

defineProps({
  groups: {
    type: Object,
    required: true,
  },
})

const showGroupModel = ref(false)

provide('show', showGroupModel)

function updateShowGroupModel(value) {
  showGroupModel.value = value
}

provide('updateModel', updateShowGroupModel)
</script>

<template>
  <div
    class="flex max-h-screen flex-col overflow-hidden rounded-md border bg-white px-3 py-4 shadow-sm dark:border-gray-700 dark:bg-gray-800"
  >
    <div class="mb-2 flex items-center justify-between px-4">
      <h2 class="text-2xl font-semibold dark:text-gray-200">Groups</h2>
      <button
        class="rounded-md bg-gradient-to-r from-indigo-500 to-purple-600 px-1 py-2 text-xs text-white transition-all duration-300 hover:bg-indigo-600 hover:from-indigo-600 hover:to-purple-700 hover:shadow dark:text-gray-100"
        @click="showGroupModel = true"
      >
        New Group
      </button>
    </div>
    <GroupListItems :groups />
  </div>
  <GroupCreateModel v-model="showGroupModel" />
</template>

<style scoped></style>
