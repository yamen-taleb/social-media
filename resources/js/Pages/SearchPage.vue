<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PostList from '@/Components/App/PostList.vue'
import SearchRecords from '@/Components/SearchRecords.vue'
import axiosClient from '@/axiosClient.js'

const props = defineProps({
  users: {
    type: Object,
    required: true,
  },
  groups: {
    type: Object,
    required: true,
  },
  posts: {
    type: Object,
    required: true,
  },
  search: {
    type: String,
    required: true,
  },
})

const usersList = ref(props.users)
const groupsList = ref(props.groups)
const isLoading = ref({
  users: false,
  groups: false,
})

const loadMore = async (type) => {
  if (isLoading.value[type]) return

  isLoading.value[type] = true

  try {
    const currentList = type === 'users' ? usersList.value : groupsList.value

    const { data } = await axiosClient.get(currentList.links.next, {
      params: {
        type,
      },
    })

    if (type === 'users') {
      usersList.value = {
        data: [...usersList.value.data, ...data.data],
        links: data.links || {},
      }
    } else if (type === 'groups') {
      groupsList.value = {
        data: [...groupsList.value.data, ...data.data],
        links: data.links || {},
      }
    }
  } catch (error) {
    console.error('Error loading more items:', error)
  } finally {
    isLoading.value[type] = false
  }
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="mx-auto mt-4 w-1/2 space-y-4">
      <!-- Users Section -->
      <div v-if="usersList.data.length > 0" class="rounded-md bg-white px-4 py-3 shadow-sm dark:bg-gray-800 dark:border dark:border-gray-700">
        <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-gray-200">People</h2>
        <SearchRecords :records="usersList.data" route-name="profile" />
        <div class="mt-1 flex justify-center">
          <button
            v-if="usersList.links?.next"
            @click="loadMore('users')"
            :disabled="isLoading.users"
            class="rounded-md px-2 text-sm text-blue-600 hover:text-blue-800 disabled:opacity-50 dark:text-blue-400 dark:hover:text-blue-300"
          >
            {{ isLoading.users ? 'Loading...' : 'Show more people' }}
          </button>
        </div>
      </div>

      <!-- Groups Section -->
      <div v-if="groupsList.data.length > 0" class="rounded-md bg-white px-4 py-3 shadow-sm dark:bg-gray-800 dark:border dark:border-gray-700">
        <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-gray-200">Groups</h2>
        <SearchRecords :records="groupsList.data" route-name="groups.show" />
        <div class="mt-1 flex justify-center">
          <button
            v-if="groupsList.links?.next"
            @click="loadMore('groups')"
            :disabled="isLoading.groups"
            class="rounded-md px-2 text-sm text-blue-600 hover:bg-blue-100 hover:text-blue-800 disabled:opacity-50 dark:text-blue-400 dark:hover:bg-gray-700 dark:hover:text-blue-300"
          >
            {{ isLoading.groups ? 'Loading...' : 'Show more groups' }}
          </button>
        </div>
      </div>

      <!-- Posts Section -->
      <PostList v-if="posts.data.length > 0" :posts="posts.data" :showCreatePost="false" />

      <!-- No Results -->
      <div
        v-if="
          usersList.data.length === 0 && groupsList.data.length === 0 && posts.data.length === 0
        "
        class="py-8 text-center text-lg text-gray-800 dark:text-gray-300"
      >
        No results found for "{{ search }}"
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped></style>
