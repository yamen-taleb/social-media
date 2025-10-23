<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  records: {
    type: Array,
    required: true,
  },
  routeName: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <div v-for="record in records">
    <Link
      :href="route(routeName, record.username || record.slug)"
      class="flex gap-2 rounded-lg px-3 py-2 hover:bg-gray-50"
    >
      <img
        :alt="record.name"
        :src="
          record.avatar ||
          record.thumbnail ||
          'http://social-media.test/storage/images/default-avatar.png'
        "
        class="h-12 w-12 shrink-0 rounded-full object-cover"
      />
      <div>
        <h3 class="truncate text-sm font-medium text-gray-900">
          {{ record.name }}
        </h3>
        <p class="truncate text-sm text-gray-500">@{{ record.username || record.slug }}</p>
      </div>
    </Link>
  </div>
</template>

<style scoped></style>
