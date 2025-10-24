<script setup>
import Download from '@/Components/Icons/Download.vue'
import Like from '@/Components/Icons/Like.vue'
import Comment from '@/Components/Icons/Comment.vue'
import PostUserHeader from '@/Components/App/PostUserHeader.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import ShowLessReadMore from '@/Components/App/ShowLessReadMore.vue'
import EditDeleteMenu from '@/Components/App/EditDeleteMenu.vue'
import useLikeRequest from '@/Composables/useLikeRequest.js'
import { computed } from 'vue'
import { MenuItem } from '@headlessui/vue'
import { ClipboardIcon, EyeIcon } from '@heroicons/vue/24/outline'
import useCopy from '@/Composables/useCopy.js'

const props = defineProps({
  post: Object,
})

const authUser = usePage().props.auth.user
const isPostOwner = computed(() => props.post.user.id === authUser.id)
const isAdmin = computed(() => props.post.group?.role === 'admin')
const isImage = (attachment) => {
  return attachment.type.startsWith('image/') || attachment.type === 'jpg'
}

const postBody = computed(() =>
  props.post.description.replace(/(#\w+)(?![^<]*<\/a>)/g, (match, group) => {
    const encodedGroup = encodeURIComponent(group)
    return `<a href="/searchPage/${encodedGroup}" class="hashtag">${group}</a>`
  })
)

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

const copyUrl = () => {
  const url = route('posts.show', props.post.id)
  useCopy(url)
}
</script>

<template>
  <div class="mb-6 rounded-md border bg-white px-3 py-4 shadow-sm">
    <div class="flex items-center justify-between px-2 py-4">
      <PostUserHeader :created_at="post.created_at" :group="post.group" :user="post.user" />
      <EditDeleteMenu
        :isAdmin="isAdmin"
        :isOwner="isPostOwner"
        @delete="deletePost"
        @edit="openEditModel"
      >
        <MenuItem v-slot="{ active }">
          <Link
            :class="[
              active ? 'bg-violet-500 text-white' : 'text-gray-900',
              'group flex w-full items-center gap-1 rounded-md px-2 py-2 text-sm',
            ]"
            :href="route('posts.show', post.id)"
          >
            <EyeIcon class="h-5 w-5" />
            Open Post
          </Link>
        </MenuItem>

        <MenuItem v-slot="{ active }">
          <button
            :class="[
              active ? 'bg-violet-500 text-white' : 'text-gray-900',
              'group flex w-full items-center gap-1 rounded-md px-2 py-2 text-sm',
            ]"
            @click="copyUrl"
          >
            <ClipboardIcon class="h-5 w-5" />
            Copy Url
          </button>
        </MenuItem>
      </EditDeleteMenu>
    </div>
    <!-- Rest of your template remains the same -->
    <div class="ck-content-output mb-3">
      <ShowLessReadMore :content="postBody" />
    </div>
    <div
      v-if="post.attachments?.length"
      :class="[post.attachments?.length === 1 ? 'grid-cols-1' : 'grid-cols-2']"
      class="grid gap-2"
    >
      <div
        v-for="(attachment, index) in post.attachments?.slice(0, 4)"
        :key="attachment.id"
        class="group relative mb-3"
      >
        <a
          v-if="!(index === 3 && post.attachments.length > 4)"
          :href="route('post-attachments.download', attachment)"
          class="absolute right-2 top-2 rounded bg-gray-700 p-1 text-white opacity-0 shadow transition-all hover:bg-gray-800 group-hover:opacity-100"
        >
          <Download />
        </a>
        <div
          v-if="index === 3 && post.attachments.length > 4"
          class="absolute inset-0 z-10 flex cursor-pointer items-center justify-center rounded bg-black/50 text-2xl text-white transition-all duration-200 hover:bg-black/60"
          @click="showAttachmentPreview(post.attachments, index)"
        >
          +{{ post.attachments.length - 4 }}
        </div>
        <template v-if="isImage(attachment) || attachment.type === 'png'">
          <img
            :class="[post.attachments?.length === 1 ? 'max-h-72 object-contain' : 'object-cover']"
            :src="attachment.path"
            alt=""
            class="aspect-square w-full rounded-md"
            @click="showAttachmentPreview(post.attachments, index)"
            :loading="index === 0 ? 'eager' : 'lazy'"
            sizes="(max-width: 640px) 100vw, 50vw"
            width="800"
            height="800"
            decoding="async"
            :fetchpriority="index === 0 ? 'high' : 'auto'"
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
        <Like :hasReaction="post.current_user_has_reaction" @click="likePost(post.id)" />
        <Comment @click="emit('showPostReview', post)" />
      </div>
    </div>
  </div>
</template>

<style scoped></style>
