<script setup>
import { computed, onUnmounted, ref, watchEffect } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ChevronLeftIcon, ChevronRightIcon, XMarkIcon } from '@heroicons/vue/24/outline/index.js'
import { isImage, isVideo } from '@/Composables/helper.js'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  attachments: {
    type: Array,
    default: () => [],
  },
  index: {
    type: Number,
    required: true,
  },
})

const emit = defineEmits(['update:modelValue', 'update:index'])
const localIndex = ref(props.index)
const localAttachments = ref(props.attachments)

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})

function closeModal() {
  show.value = false
  emit('update:index', localIndex.value)
}

function next() {
  if (localIndex.value < localAttachments.value.length - 1) {
    localIndex.value++
  }
}

function prev() {
  if (localIndex.value > 0) {
    localIndex.value--
  }
}

watchEffect(() => {
  if (Array.isArray(props.attachments)) {
    localAttachments.value = [...props.attachments]
  }
  if (typeof props.index === 'number') {
    localIndex.value = props.index
  }
})

onUnmounted(() => {
  localIndex.value = 0
  localAttachments.value = []
})

const handleKeydown = (e) => {
  if (!props.modelValue) return

  switch (e.key) {
    case 'ArrowLeft':
      prev()
      break
    case 'ArrowRight':
      next()
      break
    case 'Escape':
      closeModal()
      break
  }
}
</script>

<template>
  <TransitionRoot appear :show="show" as="template">
    <Dialog
      as="div"
      @close="closeModal"
      @keydown.esc="closeModal"
      class="relative z-10"
      @keydown="handleKeydown"
    >
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
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
              ref="dialogPanel"
              class="relative h-[calc(100vh-4rem)] w-full transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800"
              tabindex="0"
            >
              <ChevronLeftIcon
                class="absolute left-4 top-1/2 z-10 h-14 w-14 -translate-y-1/2 text-gray-500 transition-colors duration-200 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                :class="localIndex === 0 ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'"
                @click="prev"
                tabindex="0"
                aria-label="Previous image"
              />
              <ChevronRightIcon
                class="absolute right-4 top-1/2 z-10 h-14 w-14 -translate-y-1/2 text-gray-500 transition-colors duration-200 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                :class="
                  localIndex === localAttachments.length - 1
                    ? 'cursor-not-allowed opacity-50'
                    : 'cursor-pointer'
                "
                @click="next"
                tabindex="0"
                aria-label="Next image"
              />
              <XMarkIcon
                class="absolute right-4 top-4 z-10 h-8 w-8 cursor-pointer text-gray-500 transition-colors duration-200 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                @click="closeModal"
                tabindex="0"
                aria-label="Close preview"
              />
              <Transition
                enter-active-class="transition-all duration-300"
                enter-from-class="opacity-50 -ml-32"
              >
                <img
                  v-if="isImage(localAttachments[localIndex])"
                  :src="localAttachments[localIndex].path"
                  :key="localIndex"
                  loading="lazy"
                  alt="attachment"
                  class="h-full w-full object-contain"
                />
              </Transition>
              <Transition
                enter-active-class="transition-all duration-300"
                enter-from-class="opacity-50 -ml-32"
              >
                <video
                  v-if="isVideo(localAttachments[localIndex])"
                  :src="localAttachments[localIndex].path"
                  :key="localIndex"
                  controls
                  autoplay
                  class="h-full w-full object-contain"
                />
              </Transition>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<style scoped>
:deep([tabindex]:focus) {
  outline: none;
}
</style>
