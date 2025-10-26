<script setup>
import Model from '@/Components/Model.vue'
import { ref } from 'vue'
import { debounce } from 'lodash'
import { useToast } from 'vue-toastification'
import TextInput from '../../../../vendor/laravel/breeze/stubs/inertia-vue-ts/resources/js/Components/TextInput.vue'

const props = defineProps({
  group: {
    type: Object,
    required: true,
  },
})

const searchQuery = ref('')
const searchResults = ref([])
const isSearching = ref(false)

const searchUsers = async () => {
  if (searchQuery.value.length < 2) {
    searchResults.value = []
    return
  }

  isSearching.value = true
  try {
    const response = await axios.get(route('groups.users.search', props.group.slug), {
      params: { search: searchQuery.value },
    })
    searchResults.value = response.data
  } catch (error) {
    console.error('Error searching users:', error)
  } finally {
    isSearching.value = false
  }
}

const debouncedSearch = debounce(searchUsers, 300)

const addMember = async (userId) => {
  try {
    await axios.post(route('groups.members.add', props.group.slug), {
      user_id: userId,
    })

    searchResults.value = searchResults.value.filter((user) => user.id !== userId)
    searchQuery.value = ''

    useToast().success('Member added successfully')
  } catch (error) {
    console.error('Error adding member:', error)
  }
}

const close = () => {
  searchQuery.value = ''
  searchResults.value = []
}
</script>

<template>
  <Model title="Add Members" @close="close">
    <div class="mt-4">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search Users</label>
      <div class="relative mt-1">
        <TextInput
          v-model="searchQuery"
          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          placeholder="Search by name, username, or email"
          type="text"
          @input="debouncedSearch"
        />
      </div>

      <div v-if="isSearching" class="absolute inset-y-0 right-0 flex items-center pr-3">
        loading...
      </div>
      <div v-if="searchResults.length > 0" class="mt-2 max-h-60 space-y-2 overflow-y-auto">
        <div
          v-for="user in searchResults"
          :key="user.id"
          class="flex items-center justify-between rounded p-2 hover:bg-gray-50 dark:hover:bg-gray-700/30"
        >
          <div class="flex items-center">
            <img
              :alt="user.name"
              :src="user.avatar || 'http://social-media.test/storage/images/default-avatar.png'"
              class="h-10 w-10 rounded-full object-cover"
            />
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ user.name }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">@{{ user.username }}</p>
            </div>
          </div>
          <button
            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            @click="addMember(user.id)"
          >
            Add
          </button>
        </div>
      </div>
      <p
        v-else-if="searchQuery && !isSearching"
        class="mt-2 text-sm text-gray-500 dark:text-gray-300"
      >
        No users found
      </p>
    </div>
  </Model>
</template>

<style scoped></style>
