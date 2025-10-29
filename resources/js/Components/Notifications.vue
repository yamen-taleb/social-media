<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue'
import { BellIcon, XMarkIcon } from '@heroicons/vue/24/outline/index.js'
import { Link, usePage } from '@inertiajs/vue3'
import axiosClient from '@/axiosClient.js'
import { useToast } from 'vue-toastification'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const isOpen = ref(false)
const dropdown = ref(null)

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}

const closeOnClickOutside = (event) => {
  if (dropdown.value && !dropdown.value.contains(event.target)) {
    isOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', closeOnClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', closeOnClickOutside)
})

const notifications = ref(usePage().props.notifications?.data)
const unreadCount = ref(usePage().props.unread)
const nextUrl = ref(usePage().props.notifications?.links?.next)

const deleteNotification = (notification) => {
  axiosClient
    .delete(route('notifications.destroy', notification.id))
    .then(() => {
      notifications.value = notifications.value.filter((n) => n.id !== notification.id)
      if (!notification.read) unreadCount.value = unreadCount.value - 1
    })
    .catch(() => {
      useToast().error('Failed to delete notification')
    })
}

const markAllAsRead = () => {
  axiosClient
    .delete(route('notifications.markAllRead'))
    .then(() => {
      notifications.value = notifications.value.map((n) => ({ ...n, read: true }))
      unreadCount.value = 0
    })
    .catch(() => {
      useToast().error('Failed to mark all as read')
    })
}

const loadMore = () => {
  if (nextUrl.value)
    axiosClient
      .get(
        route('notifications.index'),
        {
          params: {
            page: usePage().props.notifications.meta.current_page + 1,
          },
        },
        {
          preserveScroll: true,
        }
      )
      .then(({ data }) => {
        notifications.value = [...notifications.value, ...data.data]
        nextUrl.value = data.links.next
      })
      .catch((e) => {
        useToast().error('Failed to load more notifications' + e)
      })
}
</script>

<template>
  <div class="relative" ref="dropdown">
    <button
      @click="toggleDropdown"
      class="relative rounded-full p-2 text-gray-600 transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-white dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-blue-500 dark:focus:ring-offset-gray-900"
      aria-label="Notifications"
      aria-haspopup="true"
      :aria-expanded="isOpen"
    >
      <BellIcon class="h-5 w-5" />
      <span
        v-if="unreadCount > 0"
        class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs text-white"
      >
        {{ unreadCount }}
      </span>
    </button>

    <!-- Dropdown panel -->
    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-if="isOpen"
        class="absolute right-0 z-50 mt-2 w-80 origin-top-right overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black/5 dark:bg-gray-800 dark:ring-white/10"
      >
        <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Notifications</h3>
            <button
              v-if="unreadCount > 0"
              @click="markAllAsRead"
              class="text-xs font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300"
            >
              Mark all as read
            </button>
          </div>
        </div>

        <div class="hide-scrollbar max-h-96 overflow-y-auto">
          <div v-if="notifications?.length === 0" class="px-4 py-6 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">No new notifications</p>
          </div>

          <div v-else>
            <Link
              v-for="notification in notifications"
              :key="notification.id"
              :href="notification.url"
              class="relative flex items-center gap-2 border-b border-gray-100 px-4 py-3 pr-6 text-sm transition-colors hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700"
              :class="{ 'bg-blue-50 dark:bg-gray-700/50': !notification.read }"
            >
              <img
                :src="notification.avatar"
                alt="avatar image"
                loading="lazy"
                decoding="async"
                width="56"
                height="56"
                sizes="(max-width: 640px) 100vw, 50vw"
                class="h-12 w-12 shrink-0 place-self-start rounded-full border-2 border-gray-200 object-cover transition-all hover:border-blue-700 dark:border-gray-600 dark:hover:border-indigo-800"
              />
              <div>
                <p class="text-sm text-gray-700 dark:text-gray-200">{{ notification.message }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                  {{ notification.time }}
                </p>
                <Link
                  v-if="notification.action"
                  :method="notification.action?.method"
                  :href="notification.action?.url"
                >
                  <PrimaryButton padding="px-2 py-1" class="mt-3">
                    {{ notification.action.buttonText }}
                  </PrimaryButton>
                </Link>
              </div>
              <button
                class="absolute right-2 top-2 rounded-full p-1.5 text-gray-500 transition-colors hover:bg-gray-200 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-gray-200"
                @click.prevent="deleteNotification(notification)"
                aria-label="Dismiss notification"
              >
                <XMarkIcon class="h-3.5 w-3.5" />
              </button>
            </Link>
          </div>
        </div>

        <div
          class="border-t border-gray-200 bg-gray-50 px-4 py-2 text-center dark:border-gray-700 dark:bg-gray-800/50"
          v-if="nextUrl"
        >
          <button
            @click="loadMore"
            class="block py-2 text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300"
          >
            View preview notifications
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<style scoped></style>
