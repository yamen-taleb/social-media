<script setup>
import SearchDropDown from '@/Components/App/SearchDropDown.vue'
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline/index.js'
import TextInput from '@/Components/TextInput.vue'
import { onBeforeUnmount, onMounted, ref } from 'vue'
import axiosClient from '@/axiosClient.js'
import { debounce } from 'lodash'
import { router, usePage } from '@inertiajs/vue3'

const search = ref(usePage().props.search ?? '')
const searchInput = ref(null)
const searchContainer = ref(null)
const searchUsers = ref([])
const searchGroups = ref([])
const isLoading = ref(false)
const showSearchDropdown = ref(false)

const handleClickOutside = (event) => {
  if (searchContainer.value && !searchContainer.value.contains(event.target)) {
    showSearchDropdown.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})

const focusSearchInput = async () => {
  searchInput.value?.$el?.querySelector('input')?.focus()
  if (searchGroups.value?.length || searchUsers.value?.length) showSearchDropdown.value = true
}
const searchGlobe = () => {
  if (search.value.length < 3) return
  showSearchDropdown.value = true
  isLoading.value = true
  axiosClient
    .get(route('search'), {
      params: {
        search: search.value,
      },
    })
    .then(({ data }) => {
      searchUsers.value = data.users
      searchGroups.value = data.groups
    })
    .finally(() => {
      isLoading.value = false
    })
}

const debounceSearch = debounce(searchGlobe, 500)

const searchEnter = () => {
  router.get(route('searchPage', { search: encodeURIComponent(search.value) }))
}
</script>

<template>
  <div ref="searchContainer">
    <div class="relative min-w-64 flex-1" @click="focusSearchInput">
      <TextInput
        ref="searchInput"
        v-model="search"
        placeholder="Search"
        class="w-full"
        @input="debounceSearch"
        @keydown.enter="searchEnter"
      />
      <MagnifyingGlassIcon
        class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-500 dark:text-gray-200/70"
      />
    </div>
    <Transition>
      <SearchDropDown :searchGroups :isLoading :searchUsers v-if="showSearchDropdown" />
    </Transition>
  </div>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
  transition: all 0.2s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
  margin-top: -10px;
}
</style>
