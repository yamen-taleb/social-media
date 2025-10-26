<script setup>
import { ref } from 'vue'
import { ArrowUturnLeftIcon, XMarkIcon } from '@heroicons/vue/24/outline/index.js'
import { usePage } from '@inertiajs/vue3'
import { isImage, isVideo } from '@/Composables/helper.js'

const props = defineProps({
  modelValue: {
    type: Array,
    required: true,
  },
  attachmentErrors: {
    type: Array,
    default: () => [],
  },
})
const deletedAttachmentIds = ref([])
const emit = defineEmits(['update:modelValue', 'deleteAttachment'])
const attachments = ref(props.modelValue)
const attachmentExtensions = usePage().props.attachmentExtensions || []

const removeFile = (attachment, index) => {
  if (attachment.id) {
    attachment.deleted = true
    const idExists = deletedAttachmentIds.value.some((id) => id === attachment.id)
    if (!idExists) deletedAttachmentIds.value.push(attachment.id)
    emit('deleteAttachment', deletedAttachmentIds.value)
  } else {
    attachments.value = attachments.value.filter((_, i) => i !== index)
    emit('update:modelValue', attachments.value)
  }
}

const revertRemoveFile = (attachment) => {
  attachment.deleted = false
  deletedAttachmentIds.value = deletedAttachmentIds.value.filter((id) => id !== attachment.id)
  emit('deleteAttachment', deletedAttachmentIds.value)
}
</script>

<template>
  <div
    v-if="attachmentErrors.length"
    class="mt-3 border-l-4 border-amber-500 bg-amber-100 px-3 py-2 text-gray-800 dark:border-amber-400 dark:bg-amber-50 dark:text-gray-200"
  >
    Valid extensions:
    <small class="block">{{ attachmentExtensions.join(', ') }}</small>
  </div>
  <div
    v-if="attachments?.length"
    :class="[attachments?.length === 1 ? 'grid-cols-1' : 'grid-cols-2']"
    class="relative mt-2 grid gap-2 rounded bg-gray-100 p-1 dark:border dark:border-gray-700 dark:bg-gray-800"
  >
    <div v-for="(attachment, index) in attachments" :key="index" class="group relative space-y-2">
      <XMarkIcon
        v-if="!attachment.deleted"
        class="absolute right-2 top-4 z-10 h-6 w-6 cursor-pointer rounded-full bg-white p-1 opacity-75 transition-all duration-200 hover:opacity-100 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-gray-100"
        @click="removeFile(attachment, index)"
      />
      <div
        v-if="attachment.deleted"
        class="absolute inset-0 z-10 flex cursor-pointer flex-col items-center justify-end bg-black/50 text-xs text-white transition-all duration-200 hover:bg-black/60 dark:text-gray-200"
      >
        <ArrowUturnLeftIcon
          class="absolute right-2 top-4 z-10 h-6 w-6 cursor-pointer rounded-full bg-white p-1 text-gray-600 opacity-75 transition-all duration-200 hover:opacity-100 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-gray-100"
          @click="revertRemoveFile(attachment)"
        />
        To be deleted
      </div>
      <template v-if="isImage(attachment)">
        <img
          :class="[attachments?.length === 1 ? 'object-contain' : 'object-cover']"
          :src="attachment.url || attachment.path"
          alt=""
          class="aspect-square w-full rounded-md"
        />
      </template>
      <template v-else-if="isVideo(attachment)">
        <video
          :class="[attachments?.length === 1 ? 'object-contain' : 'object-cover']"
          :src="attachment.url || attachment.path"
          controls
          class="aspect-square w-full rounded-md"
        />
      </template>
      <template v-else>
        <div
          class="flex aspect-square w-full items-center justify-center rounded-md border bg-gray-100 dark:bg-gray-700"
        >
          <span class="text-gray-500">{{ attachment.name || 'File' }}</span>
        </div>
      </template>
      <span v-if="attachmentErrors[index]" class="text-xs text-red-500">{{
        attachmentErrors[index]
      }}</span>
    </div>
  </div>
</template>

<style scoped></style>
