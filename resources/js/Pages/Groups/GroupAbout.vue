<script setup>
import { useForm } from '@inertiajs/vue3'
import GroupForm from '@/Components/App/GroupForm.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { InformationCircleIcon } from '@heroicons/vue/24/outline/index.js'
import { useToast } from 'vue-toastification'

const props = defineProps({
  group: {
    type: Object,
    required: true,
  },
  isAdmin: {
    type: Boolean,
    required: true,
  },
})

const aboutForm = useForm({
  name: props.group.name,
  auto_approval: props.group.auto_approval,
  description: props.group.description,
})

function updateGroup() {
  aboutForm.put(route('groups.update', props.group.slug), {
    preserveScroll: true,
    onSuccess: () => {
      useToast().success('Group updated successfully')
    },
    onError: () => {
      useToast().error('Failed to update group')
    },
  })
}
</script>

<template>
  <template v-if="isAdmin">
    <GroupForm :form="aboutForm" />
    <PrimaryButton @click="updateGroup">Save</PrimaryButton>
  </template>
  <div v-else class="space-y-4">
    <div v-if="group.description" class="prose max-w-none">
      <h3 class="text-lg font-medium text-gray-900">About this group</h3>
      <div
        class="mt-2 whitespace-pre-line text-gray-700"
        v-html="group.description.replace(/\n/g, '<br/>')"
      />
    </div>
    <div v-else class="rounded-md bg-blue-50 p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <InformationCircleIcon aria-hidden="true" class="h-5 w-5 text-blue-400" />
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-blue-800">No description yet</h3>
          <div class="mt-2 text-sm text-blue-700">
            <p>This group doesn't have a description yet.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
