<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  id: Number,
  avatar: String,
  name: String,
  username: String,
  showButtons: {
    type: Boolean,
    default: false,
  },
  currentRole: {
    type: String,
    default: 'member',
  },
  showRoleSelect: {
    type: Boolean,
    default: false,
  },
  isAdmin: {
    type: Boolean,
    default: false,
  },
  isOwner: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['handleRequest', 'update:role'])

const roles = ref([
  { value: 'admin', label: 'Admin' },
  { value: 'member', label: 'Member' },
])

const updateRole = (event) => {
  emit('update:role', event.target.value, props.id)
}
</script>

<template>
  <div
    class="group relative flex items-center gap-3 rounded-md bg-white p-3 shadow transition-all hover:border-b-2 hover:border-indigo-500"
  >
    <Link :href="route('profile', { user: username })" class="flex flex-1 items-center gap-3">
      <img
        :alt="name"
        :src="avatar || 'http://social-media.test/storage/images/default-avatar.png'"
        class="h-12 w-12 shrink-0 rounded-full object-cover"
      />
      <div class="min-w-0">
        <h3 class="truncate text-sm font-medium text-gray-900">{{ name }}</h3>
        <p class="truncate text-sm text-gray-500">@{{ username }}</p>
      </div>
    </Link>

    <div v-if="showButtons" class="flex shrink-0 gap-2">
      <button
        class="rounded-md bg-green-500 px-2 py-1 text-xs text-white hover:bg-green-600"
        @click.prevent.stop="emit('handleRequest', 'accept')"
      >
        Accept
      </button>
      <button
        class="rounded-md bg-red-500 px-2 py-1 text-xs text-white hover:bg-red-600"
        @click.prevent.stop="emit('handleRequest', 'reject')"
      >
        Reject
      </button>
    </div>

    <div v-else-if="showRoleSelect && isAdmin" class="shrink-0">
      <select
        :class="{
          'bg-indigo-50 text-indigo-700': currentRole === 'admin',
          'bg-gray-50 text-gray-700': currentRole === 'member',
        }"
        :disabled="isOwner"
        :value="currentRole"
        class="rounded-md border-gray-300 py-1 pl-2 pr-8 text-sm focus:border-indigo-500 focus:ring-indigo-500"
        @change="updateRole"
      >
        <option
          v-for="role in roles"
          :key="role.value"
          :value="role.value"
          class="bg-white text-gray-900"
        >
          {{ role.label }}
        </option>
      </select>
    </div>
  </div>
</template>

<style scoped>
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
  color-adjust: exact;
}
</style>

<style scoped></style>
