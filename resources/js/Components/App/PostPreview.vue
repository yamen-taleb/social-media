<script setup>
import { computed, provide, ref, watch } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { PaperAirplaneIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import PostItem from '@/Components/App/PostItem.vue'
import Comments from '@/Components/App/Comments.vue'
import AutoResizeTextarea from '@/Components/App/AutoResizeTextarea.vue'
import { usePage } from '@inertiajs/vue3'
import axiosClient from '@/axiosClient.js'
import { useToast } from 'vue-toastification'
import Loading from '@/Components/App/Loading.vue'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  post: {
    type: Object,
    required: true,
  },
})

const postOwnerId = computed(() => props.post.user.id)
provide('postOwnerId', postOwnerId)
const emit = defineEmits(['update:modelValue', 'update:index'])

const avatar = usePage().props.auth.user.avatar
const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})
const comment = ref('')
const isActiveTextarea = ref(false)
const comments = ref([])
const commentsPaginationLinks = ref({})
const isLoading = ref(false)

function closeModal() {
  show.value = false
  comment.value = ''
  isActiveTextarea.value = false
}

const submitComment = () => {
  axiosClient
    .post(route('comments.store'), {
      body: comment.value,
      post_id: props.post.id,
    })
    .then(({ data }) => {
      comment.value = ''
      comments.value.unshift(data)
      useToast().success('Comment saved')
    })
    .catch(() => {
      useToast().error('Comment has not been created')
    })
}

// Watch for modal open/close
watch(
  () => props.modelValue,
  (isOpen) => {
    if (isOpen) loadComments()
    else setTimeout(() => (comments.value = []), 300)
  },
  { immediate: true }
)

const loadComments = () => {
  isLoading.value = true
  const currentRoute = commentsPaginationLinks.value?.next_page_url || route('comments.index')

  axiosClient
    .get(currentRoute, {
      params: {
        post_id: props.post.id,
      },
    })
    .then(({ data }) => {
      comments.value = [...comments.value, ...data.comments] || []
      commentsPaginationLinks.value = data.paginationLinks || {}
    })
    .catch((error) => {
      console.error('Failed to load comments:', error)
      useToast().error('Failed to load comments')
    })
    .finally(() => {
      isLoading.value = false
    })
}
</script>

<template>
  <TransitionRoot :show="show" appear as="template">
    <Dialog as="div" class="relative z-10" @close="closeModal">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="hide-scrollbar relative max-h-[calc(100vh-4rem)] w-1/2 transform overflow-hidden overflow-y-auto rounded-2xl bg-white text-left align-middle shadow-xl transition-all"
            >
              <div class="my-3 text-center text-lg text-gray-600">{{ post.user.name }}'s post</div>
              <hr />
              <PostItem :post class="rounded-none shadow-none" />
              <Loading v-if="isLoading && comments.length === 0" />
              <Comments v-else v-model:comments="comments" />
              <div class="mt-1 flex justify-center" v-if="commentsPaginationLinks?.next_page_url">
                <button
                  @click="loadComments"
                  :disabled="isLoading"
                  class="rounded-md px-2 text-sm text-blue-600 hover:text-blue-800 disabled:opacity-50"
                >
                  {{ isLoading ? 'Loading...' : 'Show more comments' }}
                </button>
              </div>
              <XMarkIcon
                aria-label="Close preview"
                class="absolute right-4 top-4 z-10 h-8 w-8 cursor-pointer rounded-full bg-black/25 p-1 text-white transition-colors duration-200 hover:bg-black/50"
                @click="closeModal"
              />
              <div
                class="relative sticky bottom-0 left-0 z-20 flex items-center gap-1 bg-white px-3 py-2"
              >
                <img
                  :src="avatar"
                  alt=""
                  class="h-12 w-12 rounded-full border border-2 object-cover"
                  loading="lazy"
                  decoding="async"
                  width="48"
                  height="48"
                />
                <AutoResizeTextarea
                  v-model="comment"
                  :autofocus="true"
                  :isActive="isActiveTextarea"
                  placeholder="Write a comment..."
                />
                <button
                  :disabled="comment.trim() === ''"
                  class="absolute right-5 top-1/2 z-20 -translate-y-1/2"
                  type="button"
                  @click="submitComment"
                >
                  <PaperAirplaneIcon
                    :class="comment.trim() === '' ? 'cursor-not-allowed' : 'cursor-pointer'"
                    class="h-5 w-5"
                  />
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<style scoped>
:deep([tabindex]:focus) {
  outline: none;
}
</style>
