<script setup>
import { XMarkIcon } from '@heroicons/vue/24/outline/index.js'
import { useForm } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import GroupForm from '@/Components/App/GroupForm.vue'
import { computed } from 'vue'
import Model from '@/Components/Model.vue'

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
  <Model title="Create Group" @close="closeModal">
    <button
      aria-label="Close modal"
      class="absolute right-4 top-4 rounded-full p-1.5 text-gray-400 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-600"
      type="button"
      @click="closeModal"
    >
      <XMarkIcon class="h-5 w-5" />
    </button>

    <form class="mt-5 space-y-5" @submit.prevent="submit">
      <GroupForm :form />

      <div class="mt-4 flex items-center justify-end gap-2">
        <button
          class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 px-4 py-2 text-xs font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600/20 dark:hover:text-gray-100"
          type="button"
          @click="closeModal"
        >
          Cancel
        </button>
        <button
          class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-xs font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:bg-blue-400/10 dark:text-blue-400 dark:hover:bg-blue-500/20"
          @click="submit"
          :disabled="form.processing"
        >
          {{ form.processing ? 'Creating...' : 'Create' }}
        </button>
      </div>
    </form>
  </Model>
</template>
