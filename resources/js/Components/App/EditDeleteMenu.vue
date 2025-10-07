<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisVerticalIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'

const emit = defineEmits(['edit', 'delete'])
</script>

<template>
  <Menu as="div" class="relative inline-block text-left">
    <div>
      <MenuButton class="rounded-full p-2 hover:bg-gray-50" aria-label="Post options">
        <EllipsisVerticalIcon class="h-5 w-5" />
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
        class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
      >
        <div class="px-1 py-1">
          <MenuItem v-slot="{ active }">
            <button
              @click="emit('edit')"
              :class="[
                active ? 'bg-violet-500 text-white' : 'text-gray-900',
                'group flex w-full items-center gap-1 rounded-md px-2 py-2 text-sm',
              ]"
            >
              <PencilSquareIcon class="h-5 w-5" />
              Edit
            </button>
          </MenuItem>

          <MenuItem v-slot="{ active }">
            <button
              @click="emit('delete')"
              :class="[
                active ? 'bg-red-500 text-white' : 'text-gray-900',
                'group flex w-full items-center gap-1 rounded-md px-2 py-2 text-sm',
              ]"
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
