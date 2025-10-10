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
    class="mb-3 flex items-center justify-between rounded p-3 hover:bg-gray-100"
  >
    <div class="flex cursor-pointer items-center gap-3">
      <img :src="group.thumbnail" alt="image" class="h-12 w-12 flex-1 rounded-full" />
      <div>
        <h3 class="text-md font-semibold">{{ group.name }}</h3>
        <p class="text-xs text-gray-500 hover:text-gray-600">{{ group.description }}</p>
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
