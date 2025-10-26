<script setup>
import { router } from '@inertiajs/vue3'
import { ChatBubbleOvalLeftEllipsisIcon } from '@heroicons/vue/24/outline/index.js'
import { useToast } from 'vue-toastification'
import Comment from '@/Components/App/Comment.vue'

const props = defineProps({
  comments: {
    type: Array,
    default: [],
  },
  showElse: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(['editComments', 'update:comments'])

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

const updateComment = (currentComment) => {
  router.put(
    route('comments.update', currentComment.id),
    {
      body: currentComment.body,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        useToast().success('Comment updated')
        const updatedComments = props.comments.map((comment) => {
          if (currentComment.id === comment.id) {
            return {
              ...comment,
              body: currentComment.body,
            }
          }
          return comment
        })
        emit('update:comments', updatedComments)
      },
      onError: (e) => {
        useToast().error(e.body)
      },
    }
  )
}
</script>

<template>
  <div class="mt-3 space-y-4 px-4 dark:text-gray-200">
    <div
      v-for="comment in comments"
      v-if="comments.length > 0"
      :key="comment.id"
      :class="['group relative']"
    >
      <Comment
        :comment
        @delete="deleteComment(comment)"
        @update="(currentComment) => updateComment(currentComment)"
      />
    </div>

    <!-- Empty State -->
    <div v-else v-if="showElse" class="flex flex-col items-center justify-center py-8 text-center">
      <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
        <ChatBubbleOvalLeftEllipsisIcon class="h-6 w-6 text-gray-400 dark:text-gray-300" />
      </div>
      <p class="font-medium text-gray-600 dark:text-gray-300">No comments yet</p>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Be the first to comment</p>
    </div>
  </div>
</template>

<style scoped></style>
