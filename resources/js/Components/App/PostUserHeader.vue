<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  user: {
    type: [null, Object],
    required: true,
  },
  showTime: {
    type: Boolean,
    default: true,
  },
  group: {
    type: [null, Object],
  },
  created_at: {
    type: String,
    default: null,
  },
})
</script>

<template>
  <div class="flex items-center gap-3">
    <Link
      :href="
        route('profile', {
          username: user.username,
        })
      "
    >
      <img
        :src="user.avatar"
        alt="avatar image"
        loading="lazy"
        decoding="async"
        width="56"
        height="56"
        sizes="(max-width: 640px) 100vw, 50vw"
        class="h-14 w-14 rounded-full border border-2 object-cover transition-all hover:border-blue-700"
      />
    </Link>
    <div>
      <h3 class="flex items-center gap-1 font-semibold text-gray-600">
        <Link
          :href="
            route('profile', {
              username: user.username,
            })
          "
          class="hover:text-gray-800 hover:underline"
        >
          {{ user.name }}
        </Link>
        <template v-if="group?.name">
          >
          <Link :href="route('groups.show', group.slug)">
            <h3 class="hover:text-gray-800 hover:underline">
              {{ group.name }}
            </h3>
          </Link>
        </template>
      </h3>
      <small v-show="showTime" class="text-gray-500 dark:text-gray-300">
        {{ created_at }}
      </small>
    </div>
  </div>
</template>

<style scoped></style>
