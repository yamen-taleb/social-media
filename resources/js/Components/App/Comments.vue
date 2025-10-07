<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import {
  ChatBubbleOvalLeftEllipsisIcon,
  PaperAirplaneIcon,
} from '@heroicons/vue/24/outline/index.js'
import ShowLessReadMore from '@/Components/App/ShowLessReadMore.vue'
import EditDeleteMenu from '@/Components/App/EditDeleteMenu.vue'
import { useToast } from 'vue-toastification'
import AutoResizeTextarea from '@/Components/App/AutoResizeTextarea.vue'
import { ref } from 'vue'
import useLikeRequest from '@/Composables/useLikeRequest.js'

const props = defineProps({
  comments: {
    type: Array,
    default: [],
  },
})

const emit = defineEmits(['editComments', 'update:comments'])
const currentComment = ref({})

const deleteComment = (comment) => {
  if (window.confirm('Are you sure you want to delete this comment?'))
    router.delete(route('comments.destroy', comment.id), {
      preserveScroll: true,
      onSuccess: () => {
        props.comments.splice(props.comments.indexOf(comment), 1)
        useToast().success('Comment deleted')
      },
      onError: () => {
        useToast().error('Comment has not been deleted')
      },
    })
}
const editComment = (comment) => {
  currentComment.value = { ...comment }
}

const updateComment = () => {
  router.put(
    route('comments.update', currentComment.value.id),
    {
      body: currentComment.value.body,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        useToast().success('Comment updated')
        const updatedComments = props.comments.map((comment) => {
          if (currentComment.value.id === comment.id) {
            return {
              ...comment,
              body: currentComment.value.body,
            }
          }
          return comment
        })
        emit('update:comments', updatedComments)
        currentComment.value = {}
      },
      onError: (e) => {
        useToast().error(e.body)
      },
    }
  )
}

const likeComment = (comment) => {
  useLikeRequest(comment.id, 'Comment').then(({ data }) => {
    comment.num_of_reactions = data.num_of_reactions
    comment.current_user_has_reaction = data.current_user_has_reaction
  })
}
</script>

<template>
  <div class="mt-3 space-y-4 px-4">
    <div
      v-if="comments.length > 0"
      v-for="(comment, index) in comments"
      :key="index"
      class="group relative"
    >
      <div class="flex items-start justify-between">
        <div class="flex flex-1 items-start gap-3">
          <Link
            :href="
              route('profile', {
                username: comment.user.username,
              })
            "
            class="flex-shrink-0"
          >
            <img
              class="h-10 w-10 rounded-full border-2 border-white object-cover shadow-sm"
              :src="comment.user.avatar"
              :alt="comment.user.name + ' profile picture'"
            />
          </Link>

          <div class="min-w-0 flex-1">
            <div v-if="currentComment?.id === comment.id" class="relative flex max-w-full flex-col">
              <AutoResizeTextarea
                v-model="currentComment.body"
                :autofocus="true"
                :is-active="true"
              />
              <button
                type="button"
                @click="updateComment"
                :disabled="currentComment.body.trim() === ''"
                class="absolute right-3 top-1/3 z-20 -translate-y-1/3"
              >
                <PaperAirplaneIcon
                  class="h-5 w-5"
                  :class="
                    currentComment.body.trim() === '' ? 'cursor-not-allowed' : 'cursor-pointer'
                  "
                />
              </button>
              <span
                class="cursor-pointer text-sm text-blue-600 hover:text-blue-700"
                @click="currentComment = {}"
              >
                cancel
              </span>
            </div>
            <div v-else class="inline-block rounded-2xl rounded-tl-none bg-gray-100 px-4 py-2">
              <Link
                :href="
                  route('profile', {
                    username: comment.user.username,
                  })
                "
                class="text-sm font-semibold text-gray-900 hover:underline"
              >
                {{ comment.user.name }}
              </Link>

              <ShowLessReadMore :content="comment.body" class="mt-0.5 text-sm text-gray-800" />
            </div>

            <div class="ml-2 mt-1 flex items-center space-x-3">
              <span class="text-xs text-gray-500">
                {{ comment.time_ago }}
              </span>
              <button
                class="text-xs"
                :class="
                  comment.current_user_has_reaction
                    ? 'text-blue-600 hover:text-blue-700'
                    : 'text-gray-500 hover:text-gray-600'
                "
                @click="likeComment(comment)"
              >
                {{ comment.num_of_reactions > 0 ? comment.num_of_reactions : '' }}
                Like
              </button>
              <button class="text-xs text-gray-500 hover:text-gray-700">Reply</button>
            </div>
          </div>
        </div>
        <EditDeleteMenu
          v-if="
            usePage().props.auth.user.id === comment.user.id && currentComment?.id !== comment.id
          "
          class="opacity-0 transition-all duration-200 group-hover:opacity-100"
          @delete="deleteComment(comment)"
          @edit="editComment(comment)"
        />
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="flex flex-col items-center justify-center py-8 text-center">
      <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
        <ChatBubbleOvalLeftEllipsisIcon class="h-6 w-6 text-gray-400" />
      </div>
      <p class="font-medium text-gray-600">No comments yet</p>
      <p class="mt-1 text-sm text-gray-500">Be the first to comment</p>
    </div>
  </div>
</template>

<style scoped></style>
