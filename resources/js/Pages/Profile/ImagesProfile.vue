<script setup>
import { InfiniteScroll } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import AttachmentsPreview from '@/Components/App/AttachmentsPreview.vue'

const props = defineProps({
  images: {
    type: Object,
    required: true,
  },
})

const showAttachmentPreview = ref(false)
const attachmentsPreview = reactive({
  attachments: [],
  index: 0,
})

const showAttachmentPreviewModel = (index) => {
  showAttachmentPreview.value = true
  attachmentsPreview.attachments = props.images.data
  attachmentsPreview.index = index
}
</script>
<template>
  <div v-if="images.data?.length > 0">
    <InfiniteScroll data="images" class="grid grid-cols-4 gap-3" preserve-url>
      <div v-for="(image, index) in images.data" :key="image.id">
        <img
          :src="image.path"
          alt="profile image"
          class="aspect-square w-full cursor-pointer rounded-md object-cover"
          @click="showAttachmentPreviewModel(index)"
          :loading="index === 0 ? 'eager' : 'lazy'"
          sizes="(max-width: 640px) 100vw, 50vw"
          width="800"
          height="800"
          decoding="async"
          :fetchpriority="index === 0 ? 'high' : 'auto'"
        />
      </div>
    </InfiniteScroll>
  </div>
  <div v-else class="text-center text-gray-500 dark:text-gray-400">No Photos</div>
  <AttachmentsPreview
    v-model="showAttachmentPreview"
    :attachments="attachmentsPreview.attachments"
    :index="attachmentsPreview.index"
    @update:index="attachmentsPreview.index = $event"
  />
</template>
