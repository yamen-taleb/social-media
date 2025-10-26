<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisVerticalIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  isOwner: {
    type: Boolean,
    default: false,
  },
  isAdmin: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['edit', 'delete'])
</script>

<template>
  <Menu as="div" class="relative inline-block text-left">
    <div>
      <MenuButton
        aria-label="Post options"
        class="rounded-full p-2 hover:bg-gray-50 dark:hover:bg-gray-700"
      >
        <EllipsisVerticalIcon class="h-5 w-5 text-gray-600 dark:text-gray-300" />
      </MenuButton>
    </div>

    <transition
      enter-active-class="transition duration-100 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="transition duration-75 ease-in"
      leave-from-class="transform scale-100 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <MenuItems
        class="absolute right-0 z-30 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none dark:divide-gray-700 dark:bg-gray-800 dark:ring-gray-700"
      >
        <div class="px-1 py-1">
          <slot />
          <MenuItem v-if="isOwner" v-slot="{ active }">
            <button
              :class="[
                active ? 'bg-violet-500 text-white' : 'text-gray-900 dark:text-gray-100',
                'group flex w-full items-center gap-1 rounded-md px-2 py-2 text-sm',
              ]"
              @click="emit('edit')"
            >
              <PencilSquareIcon class="h-5 w-5" />
              Edit
            </button>
          </MenuItem>

          <MenuItem v-if="isAdmin || isOwner" v-slot="{ active }">
            <button
              :class="[
                active ? 'bg-red-500 text-white' : 'text-gray-900 dark:text-gray-100',
                'group flex w-full items-center gap-1 rounded-md px-2 py-2 text-sm',
              ]"
              @click="emit('delete')"
            >
              <TrashIcon class="h-5 w-5" />
              Delete
            </button>
          </MenuItem>
        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>
