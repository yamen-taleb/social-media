<script setup>
import PostCreate from '@/Components/App/PostCreate.vue'
import PostItem from '@/Components/App/PostItem.vue'
import PostEditModel from '@/Components/App/PostEditModel.vue'
import { reactive, ref } from 'vue'
import AttachmentsPreview from '@/Components/App/AttachmentsPreview.vue'
import PostPreview from '@/Components/App/PostPreview.vue'
import { InfiniteScroll } from '@inertiajs/vue3'

const props = defineProps({
  posts: {
    type: Array,
    required: true,
  },
  group_id: {
    type: Number,
    default: null,
  },
  showCreatePost: {
    type: Boolean,
    default: true,
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
  if (post) {
    // If a post is provided, use it for editing
    currentPost.value = post
  } else {
    // If no post is provided, create a new empty post
    currentPost.value = {
      id: null,
      title: '',
      description: '',
      attachments: null,
      group_id: props.group_id,
      is_new: true,
    }
  }
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
  <div>
    <PostCreate v-if="showCreatePost" @createPost="openEditModel" />
    <InfiniteScroll data="posts">
      <div v-for="post in posts" :key="post.id">
        <PostItem
          :post="post"
          @editClick="openEditModel"
          @showAttachmentPreview="showAttachmentPreviewModel"
          @showPostReview="showPostReviewModel"
        />
      </div>
    </InfiniteScroll>
  </div>
  <PostEditModel v-model="showEditModel" :post="currentPost" @hide="currentPost = {}" />

  <AttachmentsPreview
    v-model="showAttachmentPreview"
    :attachments="attachmentsPreview.attachments"
    :index="attachmentsPreview.index"
    @update:index="attachmentsPreview.index = $event"
  />

  <PostPreview v-model="showPostReview" :post="currentPostReview" />
</template>

<style scoped></style>
