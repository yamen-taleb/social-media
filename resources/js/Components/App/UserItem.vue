<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  id: Number,
  avatar: String,
  name: String,
  username: String,
  showButtons: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['handleRequest'])
</script>

<template>
  <Link
    :href="
      route('profile', {
        user: username,
      })
    "
    class="flex cursor-pointer items-center gap-3 rounded-md p-3 shadow hover:bg-gray-50"
  >
    <img
      :src="avatar || 'http://social-media.test/storage/images/default-avatar.png'"
      alt="image"
      class="h-12 w-12 rounded-full object-cover"
    />
    <div class="flex flex-1 items-center justify-between">
      <h3 class="text-md font-semibold">{{ name }}</h3>

      <div v-if="showButtons" class="flex gap-2">
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
    </div>
  </Link>
</template>

<style scoped></style>
