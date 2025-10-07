<script setup>
import { useForm } from '@inertiajs/vue3'

const post = useForm({
  title: null,
  description: null,
  attachments: null,
  group_id: null,
})

// const submitPost = () => {
//     postForm.post(route('posts.store'), {
//         preserveScroll: true,
//         onSuccess: () => {
//             toast.success('Post created successfully');
//             postForm.reset();
//             postCreation.value = false;
//         },
//
//         onError: (e) => {
//             if (typeof e === 'object') {
//                 const firstErrorKey = Object.keys(e)[0];
//                 toast.error(e[firstErrorKey]);
//             }
//             else
//                 toast.error('Post failed to create');
//         }
//     });
// }

const emit = defineEmits(['createPost'])

const createPost = () => {
  emit('createPost')
}
</script>

<template>
  <div class="mb-3 rounded-md border bg-white px-2 py-3 shadow-sm">
    <div
      class="min-h-[44px] w-full cursor-pointer resize-none rounded-lg border border-gray-300 bg-white p-3 text-gray-500 transition-all duration-200 hover:border-indigo-500 hover:bg-gray-50 hover:ring-2 hover:ring-indigo-200"
      @click="createPost"
    >
      What's on your mind?
    </div>
    <!--        <div class="flex justify-end gap-2" v-if="postCreation">-->
    <!--            <button type="button" class="rounded-md px-3 py-2 text-sm font-semibold text-gray-900 bg-gray-200 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-300 relative">-->
    <!--                Attach Files-->
    <!--                <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"/>-->
    <!--            </button>-->
    <!--            <button-->
    <!--                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"-->
    <!--                type="submit"-->
    <!--            >-->
    <!--                {{postForm.processing ? 'Posting...' : 'Post'}}-->
    <!--            </button>-->
    <!--        </div>-->
  </div>
</template>

<style scoped></style>
