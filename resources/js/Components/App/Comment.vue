<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import EditDeleteMenu from '@/Components/App/EditDeleteMenu.vue'
import AutoResizeTextarea from '@/Components/App/AutoResizeTextarea.vue'
import { PaperAirplaneIcon } from '@heroicons/vue/24/outline/index.js'
import ShowLessReadMore from '@/Components/App/ShowLessReadMore.vue'
import useLikeRequest from '@/Composables/useLikeRequest.js'
import { ref } from 'vue'
import axiosClient from '@/axiosClient.js'
import { useToast } from 'vue-toastification'
import Comments from '@/Components/App/Comments.vue'

const props = defineProps({
  comment: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['delete', 'update'])
const currentComment = ref({})
const reply = ref()
const showReply = ref(false)
const subComments = ref([])

const likeComment = () => {
  useLikeRequest(props.comment.id, 'Comment').then(({ data }) => {
    props.comment.num_of_reactions = data.num_of_reactions
    props.comment.current_user_has_reaction = data.current_user_has_reaction
  })
}
const editComment = () => {
  currentComment.value = { ...props.comment }
}

const updateComment = async () => {
  await emit('update', currentComment.value)
  currentComment.value = {}
}

const createReply = () => {
  axiosClient
    .post(route('comments.store'), {
      body: reply.value,
      parent_id: props.comment.id,
      post_id: props.comment.post_id,
    })
    .then(({ data }) => {
      reply.value = ''
      showReply.value = false
      subComments.value.unshift(data)
      useToast().success('Comment saved')
    })
    .catch(() => {
      useToast().error('Comment has not been created')
    })
}
const loadSubComments = () => {
  axiosClient
    .get(route('comments.index'), {
      params: {
        parent_id: props.comment.id,
      },
    })
    .then(({ data }) => {
      subComments.value = data.comments || []
    })
    .catch((error) => {
      console.error('Failed to load comments:', error)
      useToast().error('Failed to load comments')
    })
}
</script>

<template>
  <div class="relative flex items-start justify-between">
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
          :alt="comment.user.name + ' profile picture'"
          :src="comment.user.avatar"
          class="h-10 w-10 rounded-full border-2 border-white object-cover shadow-sm"
        />
      </Link>

      <div class="min-w-0 flex-1">
        <div v-if="currentComment?.id === comment.id" class="relative flex max-w-full flex-col">
          <AutoResizeTextarea v-model="currentComment.body" :autofocus="true" :is-active="true" />
          <button
            :disabled="currentComment.body.trim() === ''"
            class="absolute right-3 top-1/3 z-20 -translate-y-1/3"
            type="button"
            @click="updateComment"
          >
            <PaperAirplaneIcon
              :class="currentComment.body.trim() === '' ? 'cursor-not-allowed' : 'cursor-pointer'"
              class="h-5 w-5"
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
            :class="
              comment.current_user_has_reaction
                ? 'text-blue-600 hover:text-blue-700'
                : 'text-gray-500 hover:text-gray-600'
            "
            class="text-xs"
            @click="likeComment"
          >
            {{ comment.num_of_reactions > 0 ? comment.num_of_reactions : '' }}
            Like
          </button>
          <button
            v-if="comment.parent_id === null"
            class="text-xs text-gray-500 hover:text-gray-700"
            @click="showReply = !showReply"
          >
            Reply
          </button>
        </div>

        <!-- Nested Comments -->
        <Comments
          v-if="subComments.length > 0"
          v-model:comments="subComments"
          :showElse="false"
          class="mt-2"
        />
        <button
          v-else
          v-if="comment.num_of_replies > 0"
          class="ml-2 mt-1 text-xs text-gray-500"
          @click="loadSubComments"
        >
          View
          {{
            comment.num_of_replies > 1
              ? comment.num_of_replies + ' replies'
              : comment.num_of_replies + ' reply'
          }}
        </button>
        <div
          v-if="showReply"
          class="relative ml-10 mt-4 flex max-w-full flex-col before:absolute before:-left-8 before:top-0 before:h-10 before:w-8 before:rounded-bl-2xl before:border-b-2 before:border-l-2 before:border-gray-300"
        >
          <AutoResizeTextarea v-model="reply" :autofocus="true" :is-active="true" />
          <button
            :disabled="reply?.trim() === ''"
            class="absolute right-3 top-1/3 -translate-y-1/3"
            type="button"
            @click="createReply"
          >
            <PaperAirplaneIcon
              :class="reply?.trim() === '' ? 'cursor-not-allowed' : 'cursor-pointer'"
              class="h-5 w-5"
            />
          </button>
          <span
            class="cursor-pointer text-sm text-blue-600 hover:text-blue-700"
            @click="showReply = false"
          >
            cancel
          </span>
        </div>
      </div>
    </div>
    <EditDeleteMenu
      v-if="usePage().props.auth.user.id === comment.user.id && currentComment?.id !== comment.id"
      class="opacity-0 transition-all duration-200 group-hover:opacity-100"
      @delete="emit('delete', comment)"
      @edit="editComment"
    />
  </div>
</template>

<style scoped></style>
