<script setup>
import { computed, defineAsyncComponent, ref, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import PostUserHeader from '@/Components/App/PostUserHeader.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import { PaperClipIcon } from '@heroicons/vue/24/outline'
import PostUploadedImage from '@/Components/App/PostUploadedImage.vue'
// Lazy-load the heavy editor so it doesn't load on initial page render
const RichEditor = defineAsyncComponent(() => import('@/Components/App/RichEditor.vue'))

const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
  modelValue: Boolean,
})
const authUser = usePage().props.auth.user

const attachments = ref([])
const attachmentErrors = ref([])
const toast = useToast()
const postForm = useForm({
  title: null,
  description: props.post.description,
  attachments: [],
  deletedAttachmentsIds: [],
  group_id: props.post.group_id,
  _method: 'post',
})

const emit = defineEmits(['update:modelValue', 'hide'])

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})

const closeModal = () => {
  setTimeout(() => emit('hide'), 300)
  show.value = false
  postForm.reset()
  attachments.value = []
  attachmentErrors.value = []
}
const submit = () => {
  attachmentErrors.value = []
  postForm.attachments = attachments.value
    ?.map((attachment) => attachment.file)
    .filter((file) => file !== null && file !== undefined)
  if (props.post.is_new) submitPost()
  else updatePost()
}
const getOptions = (isNew = false) => ({
  preserveScroll: true,
  onSuccess: () => {
    toast.success(`Post ${isNew ? 'created' : 'updated'} successfully`)
    closeModal()
  },
  onError: (e) => {
    for (const key in e) {
      if (key.includes('.')) {
        const [, index] = key.split('.')
        attachmentErrors.value[index] = e[key]
      }
    }
    if (typeof e === 'object') {
      const firstErrorKey = Object.keys(e)[0]
      toast.error(e[firstErrorKey])
    } else {
      toast.error(`Post failed to ${isNew ? 'create' : 'update'}`)
    }
  },
})
const updatePost = () => {
  postForm._method = 'put'
  postForm.post(
    route('posts.update', {
      id: props.post.id,
    }),
    getOptions(false)
  )
}

const submitPost = () => {
  postForm.post(route('posts.store'), getOptions(true))
}
watch(
  () => props.post,
  (newValue) => {
    postForm.description = newValue.description
    postForm.group_id = newValue.group_id
    if (JSON.stringify(newValue) !== '{}' && !newValue.is_new)
      attachments.value.push(...newValue?.attachments)
  }
)

const handleFileChange = async (e) => {
  for (const file of e.target.files) {
    const fileExists = attachments.value.some(
      (attachment) => attachment.file?.name === file.name && attachment.file?.size === file.size
    )
    if (!fileExists) {
      const myFile = {
        file,
        url: await readFile(file),
      }
      attachments.value.unshift(myFile)
    }
  }
  e.target.value = null
}

async function readFile(file) {
  return new Promise((res, rej) => {
    if (isImage(file)) {
      const reader = new FileReader()
      reader.onload = () => {
        res(reader.result)
      }
      reader.onerror = rej
      reader.readAsDataURL(file)
    } else {
      res(null)
    }
  })
}

const isImage = (attachment) => {
  return attachment.type.startsWith('image/') || attachment.type === 'jpg'
}
</script>

<template>
  <teleport to="body">
    <TransitionRoot :show="show" appear as="template">
      <Dialog as="div" class="relative z-10" @close="closeModal">
        <div class="fixed inset-0 overflow-y-auto bg-black/30 backdrop-blur-sm">
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
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
              >
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-800">
                  {{ post.is_new ? 'Create Post' : 'Update Post' }}
                </DialogTitle>
                <div class="mt-4">
                  <div class="text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                      <Transition>
                        <PostUserHeader
                          v-if="show"
                          :show-time="false"
                          :user="post.is_new ? authUser : post.user"
                          class="mb-3"
                        />
                      </Transition>
                      <button
                        class="relative rounded-md bg-gradient-to-r from-indigo-500 to-purple-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all duration-300 hover:from-indigo-600 hover:to-purple-700 focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2"
                        title="Select files"
                        type="button"
                      >
                        <PaperClipIcon class="size-6 text-white" />
                        <input
                          class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                          multiple
                          type="file"
                          @change="handleFileChange"
                        />
                      </button>
                    </div>
                    <RichEditor v-model="postForm.description" autofocus />
                    <PostUploadedImage
                      v-model="attachments"
                      :attachment-errors
                      @delete-attachment="postForm.deletedAttachmentsIds = $event"
                    />
                  </div>
                </div>

                <div class="mt-4 flex items-center justify-end gap-2">
                  <button
                    class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                    type="submit"
                    @click="closeModal"
                  >
                    Cancel
                  </button>
                  <button
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="submit"
                  >
                    Save
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </teleport>
</template>
<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
