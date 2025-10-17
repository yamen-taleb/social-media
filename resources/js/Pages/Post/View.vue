<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PostItem from '@/Components/App/PostItem.vue'
import PostPreview from '@/Components/App/PostPreview.vue'
import AttachmentsPreview from '@/Components/App/AttachmentsPreview.vue'
import { reactive, ref } from 'vue'
import PostEditModel from '@/Components/App/PostEditModel.vue'

defineProps({
  post: {
    type: Object,
    required: true,
  },
})

const currentPost = ref({})
const showEditModel = ref(false)
const showPostReview = ref(false)
const currentPostReview = ref({})
const showAttachmentPreview = ref(false)
const attachmentsPreview = reactive({
  attachments: [],
  index: 0,
})

const openEditModel = (post = null) => {
  currentPost.value = post
  showEditModel.value = true
}

const showAttachmentPreviewModel = (attachments, index) => {
  showAttachmentPreview.value = true
  attachmentsPreview.attachments = attachments
  attachmentsPreview.index = index
}

const showPostReviewModel = (post) => {
  showPostReview.value = true
  currentPostReview.value = post
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="mx-auto mt-2 w-2/4">
      <PostItem
        :post
        @editClick="openEditModel"
        @showAttachmentPreview="showAttachmentPreviewModel"
        @showPostReview="showPostReviewModel"
      />
    </div>
    <PostEditModel v-model="showEditModel" :post="currentPost" @hide="currentPost = {}" />
    <AttachmentsPreview
      v-model="showAttachmentPreview"
      :attachments="attachmentsPreview.attachments"
      :index="attachmentsPreview.index"
      @update:index="attachmentsPreview.index = $event"
    />

    <PostPreview v-model="showPostReview" :post="currentPostReview" />
  </AuthenticatedLayout>
</template>

<style scoped></style>
