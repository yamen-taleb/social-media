<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import Download from '@/Components/Icons/Download.vue'
import Like from '@/Components/Icons/Like.vue'
import Comment from '@/Components/Icons/Comment.vue'
import PostUserHeader from '@/Components/App/PostUserHeader.vue'
import axiosClient from '@/axiosClient.js'
import { router, usePage } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import ShowLessReadMore from '@/Components/App/ShowLessReadMore.vue'
import EditDeleteMenu from '@/Components/App/EditDeleteMenu.vue'
import useLikeRequest from '@/Composables/useLikeRequest.js'

const props = defineProps({
  post: Object,
})

const isImage = (attachment) => {
  return attachment.type.startsWith('image/')
}

const emit = defineEmits(['editClick', 'showAttachmentPreview', 'showPostReview'])
const openEditModel = () => {
  emit('editClick', props.post)
}

const showAttachmentPreview = (attachments, index) => {
  emit('showAttachmentPreview', attachments, index)
}

const likePost = (postId) => {
  useLikeRequest(postId).then((res) => {
    props.post.num_of_reactions = res.data.num_of_reactions
    props.post.current_user_has_reaction = res.data.current_user_has_reaction
  })
}

const deletePost = () => {
  if (window.confirm('Are you sure you want to delete this post?')) {
    router.delete(route('posts.destroy', props.post.id), {
      preserveScroll: true,
      onSuccess: () => {
        useToast().success('Post deleted successfully')
      },
      onError: () => {
        useToast().error('Post deleted failed')
      },
    })
  }
}
</script>

<template>
  <div class="mb-6 rounded-md border bg-white px-3 py-4 shadow-sm">
    <div class="flex items-center justify-between px-2 py-4">
      <PostUserHeader :user="post.user" :created_at="post.created_at" :group="post.group" />
      <EditDeleteMenu
        v-if="post.user.id === usePage().props.auth.user.id"
        @edit="openEditModel"
        @delete="deletePost"
      />
    </div>
    <!-- Rest of your template remains the same -->
    <div class="ck-content-output mb-3">
      <ShowLessReadMore :content="post.description" />
    </div>
    <div
      class="grid gap-2"
      :class="[post.attachments?.length === 1 ? 'grid-cols-1' : 'grid-cols-2']"
      v-if="post.attachments?.length"
    >
      <div
        v-for="(attachment, index) in post.attachments?.slice(0, 4)"
        :key="attachment.id"
        class="group relative mb-3"
      >
        <a
          :href="route('post-attachments.download', attachment)"
          v-if="!(index === 3 && post.attachments.length > 4)"
          class="absolute right-2 top-2 rounded bg-gray-700 p-1 text-white opacity-0 shadow transition-all hover:bg-gray-800 group-hover:opacity-100"
        >
          <Download />
        </a>
        <div
          v-if="index === 3 && post.attachments.length > 4"
          class="absolute inset-0 z-10 flex cursor-pointer items-center justify-center bg-black/50 text-2xl text-white transition-all duration-200 hover:bg-black/60"
        >
          +{{ post.attachments.length - 4 }}
        </div>
        <template v-if="isImage(attachment) || attachment.type === 'png'">
          <img
            @click="showAttachmentPreview(post.attachments, index)"
            :src="attachment.path"
            alt=""
            class="aspect-square w-full rounded-md"
            :class="[post.attachments?.length === 1 ? 'max-h-72 object-contain' : 'object-cover']"
          />
        </template>
        <template v-else>
          <div
            class="flex aspect-square w-full items-center justify-center rounded-md border bg-gray-100"
          >
            <span class="text-gray-500">{{ attachment.name || 'File' }}</span>
          </div>
        </template>
      </div>
    </div>
    <div class="mt-1 flex flex-col gap-2">
      <div class="flex items-center justify-between px-2">
        <div class="ml-1 flex items-center text-sm text-gray-500">
          <span v-if="post.num_of_reactions > 0">
            {{ post.num_of_reactions }}
            {{ post.num_of_reactions === 1 ? 'like' : 'likes' }}
          </span>
        </div>
        <div
          v-if="post.num_of_comments > 0"
          class="ml-1 flex cursor-pointer items-center text-sm text-gray-500 hover:text-gray-700 hover:underline"
          @click="emit('showPostReview', post)"
        >
          <span>
            {{ post.num_of_comments }}
            {{ post.num_of_comments === 1 ? 'Comment' : 'Comments' }}
          </span>
        </div>
      </div>
      <div class="flex gap-2">
        <Like @click="likePost(post.id)" :hasReaction="post.current_user_has_reaction" />
        <Comment @click="emit('showPostReview', post)" />
      </div>
    </div>
  </div>
</template>

<style scoped></style>
