<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  group: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <Link
    :href="route('groups.show', group.slug)"
    class="group mb-3 flex items-center justify-between rounded p-3 transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-700"
  >
    <div class="flex cursor-pointer items-center gap-3">
      <img
        :src="group.thumbnail"
        alt="Group thumbnail"
        class="h-12 w-12 flex-1 rounded-full border border-gray-200 dark:border-gray-600"
        loading="lazy"
      />
      <div class="overflow-hidden">
        <h3 class="text-md truncate font-semibold text-gray-900 dark:text-gray-100">
          {{ group.name }}
        </h3>
        <p
          class="text-xs text-gray-500 transition-colors group-hover:text-gray-600 dark:text-gray-400 dark:group-hover:text-gray-300"
        >
          {{ group.description || 'No description' }}
        </p>
      </div>
    </div>
    <span
      :class="group.status.name === 'Pending' ? group.status.class : group.role.class"
      class="inset-ring inline-flex items-center rounded-md px-2 py-1 text-xs"
    >
      {{ group.status.name === 'Pending' ? group.status.name : group.role.name }}
    </span>
  </Link>
</template>

<style scoped></style>
