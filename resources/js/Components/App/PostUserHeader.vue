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
        class="h-14 w-14 rounded-full border border-2 object-cover transition-all hover:border-blue-700"
        :src="user.avatar"
        alt=""
      />
    </Link>
    <div>
      <h3 class="flex items-center gap-1 font-semibold text-gray-600">
        <Link
          class="hover:text-gray-800 hover:underline"
          :href="
            route('profile', {
              username: user.username,
            })
          "
        >
          {{ user.name }}
        </Link>
        <template v-if="group?.name">
          >
          <a href="javascript:void(0)">
            <h3 class="hover:text-gray-800 hover:underline">
              {{ group.name }}
            </h3>
          </a>
        </template>
      </h3>
      <small v-show="showTime" class="text-gray-400">
        {{ created_at }}
      </small>
    </div>
  </div>
</template>

<style scoped></style>
