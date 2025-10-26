<script setup>
import { inject } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

const props = defineProps({
  title: {
    type: String,
    default: 'Model',
  },
})

const emit = defineEmits(['close'])

const show = inject('show')
const updateModel = inject('updateModel')

const closeModal = () => {
  updateModel(false)
  emit('close')
}
</script>

<template>
  <teleport to="body">
    <TransitionRoot :show="show" appear as="template">
      <Dialog as="div" class="relative z-10" @close="closeModal">
        <div class="fixed inset-0 overflow-y-auto bg-black/30 backdrop-blur-sm">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800"
              >
                <DialogTitle
                  as="h3"
                  class="text-lg font-medium leading-6 text-gray-800 dark:text-gray-200"
                >
                  {{ title }}
                </DialogTitle>
                <slot />
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </teleport>
</template>
<style scoped></style>
