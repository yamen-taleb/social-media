<script setup>
import Loading from '@/Components/App/Loading.vue'
import SearchRecords from '@/Components/SearchRecords.vue'

defineProps({
  isLoading: {
    type: Boolean,
    required: true,
  },
  searchUsers: {
    type: Array,
    required: true,
  },
  searchGroups: {
    type: Array,
    required: true,
  },
})
</script>

<template>
  <div class="absolute top-16 z-10 mt-1 w-full rounded-md bg-white shadow-lg dark:bg-gray-800 dark:border dark:border-gray-700">
    <Loading v-if="isLoading" class="text-center text-gray-600 dark:text-gray-300" />
    <div v-else>
      <div v-if="searchUsers.length > 0 || searchGroups.length > 0">
        <div v-if="searchUsers.length > 0" class="space-y-2 p-2">
          <h2 class="font-medium text-gray-700 dark:text-gray-300">People</h2>
          <SearchRecords routeName="profile" :records="searchUsers" />
        </div>
        <div v-if="searchGroups.length > 0" class="space-y-2 p-2">
          <h2 class="font-medium text-gray-700 dark:text-gray-300">Groups</h2>
          <SearchRecords routeName="groups.show" :records="searchGroups" />
        </div>
      </div>
      <div v-else class="px-3 py-2 text-center text-gray-600 dark:text-gray-400">No match found</div>
    </div>
  </div>
</template>

<style scoped></style>
