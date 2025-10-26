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
        class="h-14 w-14 rounded-full border-2 border-gray-200 object-cover transition-all hover:border-blue-700 dark:border-gray-600 dark:hover:border-indigo-800"
      />
    </Link>
    <div>
      <h3 class="flex items-center gap-1 font-semibold text-gray-600 dark:text-gray-300">
        <Link
          :href="
            route('profile', {
              username: user.username,
            })
          "
          class="hover:text-gray-800 hover:underline dark:hover:text-gray-100"
        >
          {{ user.name }}
        </Link>
        <template v-if="group?.name">
          <span class="text-gray-400 dark:text-gray-500">></span>
          <Link :href="route('groups.show', group.slug)">
            <span
              class="hover:text-gray-800 hover:underline dark:text-blue-400 dark:hover:text-blue-300"
            >
              {{ group.name }}
            </span>
          </Link>
        </template>
      </h3>
      <small v-show="showTime" class="text-sm text-gray-500 dark:text-gray-400">
        {{ created_at }}
      </small>
    </div>
  </div>
</template>
