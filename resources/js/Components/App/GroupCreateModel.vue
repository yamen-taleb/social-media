<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { computed } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline/index.js'
import TextInput from '@/Components/TextInput.vue'
import { useForm } from '@inertiajs/vue3'
import Checkbox from '@/Components/Checkbox.vue'
import AutoResizeTextarea from '@/Components/App/AutoResizeTextarea.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import { useToast } from 'vue-toastification'

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
                  <div>
                    <InputLabel class="mb-1.5" for="name" value="Group Name" />
                    <TextInput
                      id="name"
                      v-model="form.name"
                      autofocus
                      class="w-full"
                      placeholder="Enter group name"
                      required
                      type="text"
                    />
                    <InputError :message="form.errors.name" class="mt-1" />
                  </div>

                  <div>
                    <InputLabel class="mb-1.5" for="description" value="Description" />
                    <AutoResizeTextarea
                      id="description"
                      v-model="form.description"
                      class="w-full"
                      placeholder="What is this group about?"
                    />
                    <InputError :message="form.errors.description" class="mt-1" />
                  </div>

                  <div class="flex items-start space-x-3 rounded-lg bg-gray-50 p-4">
                    <Checkbox
                      id="auto_approval"
                      v-model:checked="form.auto_approval"
                      class="mt-0.5"
                    />
                    <div class="flex-1">
                      <label class="cursor-pointer" for="auto_approval">
                        <span class="block font-medium text-gray-900">Auto-approve members</span>
                        <span class="text-sm text-gray-500">
                          New members will be automatically approved to join
                        </span>
                      </label>
                    </div>
                  </div>

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

<style scoped></style>
