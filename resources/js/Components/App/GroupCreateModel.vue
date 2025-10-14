<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { computed } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline/index.js'
import { useForm } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import GroupForm from '@/Components/App/GroupForm.vue'

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true,
  },
})
const emit = defineEmits(['update:modelValue'])

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})

const form = useForm({
  name: '',
  description: '',
  auto_approval: false,
})

const closeModal = () => {
  form.reset()
  form.clearErrors()
  show.value = false
}

const submit = () => {
  form.post(route('groups.store'), {
    onSuccess: () => {
      useToast().success('Group created successfully')
      closeModal()
    },
  })
}
</script>

<template>
  <teleport to="body">
    <TransitionRoot :show="show" appear as="template">
      <Dialog as="div" class="relative z-[60]" @close="closeModal">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
        </TransitionChild>
        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-8 text-left align-middle shadow-2xl transition-all"
              >
                <button
                  aria-label="Close modal"
                  class="absolute right-4 top-4 rounded-full p-1.5 text-gray-400 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-600"
                  type="button"
                  @click="closeModal"
                >
                  <XMarkIcon class="h-5 w-5" />
                </button>

                <DialogTitle as="h3" class="mb-6 text-2xl font-semibold text-gray-900">
                  Create New Group
                </DialogTitle>

                <form class="space-y-5" @submit.prevent="submit">
                  <GroupForm :form />

                  <div class="flex items-center justify-end gap-3 pt-2">
                    <button
                      class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100"
                      type="button"
                      @click="closeModal"
                    >
                      Cancel
                    </button>
                    <button
                      :disabled="form.processing"
                      class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                      @click="submit"
                    >
                      {{ form.processing ? 'Creating...' : 'Create Group' }}
                    </button>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </teleport>
</template>
