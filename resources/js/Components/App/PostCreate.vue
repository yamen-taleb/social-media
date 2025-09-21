<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";

const postCreation = ref(false);
const textarea = ref(null);
const toast = useToast();

const postForm = useForm({
    title: null,
    description: null,
    attachments: null,
    group_id: null,
})
const adjustHeight = () => {
    if (textarea.value) {
        textarea.value.style.height = 'auto';
        textarea.value.style.height = `${Math.min(textarea.value.scrollHeight, 200)}px`;
    }
}

const submitPost = () => {
    postForm.post(route('posts.store'), {
        onSuccess: () => {
            toast.success('Post created successfully');
            postForm.reset();
            postCreation.value = false;
            window.location.reload();
        },

        onError: (e) => {
            if (typeof e === 'object') {
                const firstErrorKey = Object.keys(e)[0];
                toast.error(e[firstErrorKey]);
            }
            else
                toast.error('Post failed to create');
        }
    });
}
</script>

<template>
    <form @submit.prevent="submitPost" class="py-3 px-2 border rounded-md bg-white shadow-sm mb-3">
        <textarea
            v-model="postForm.description"
            ref="textarea"
            class="w-full p-3 text-gray-700 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 resize-none overflow-hidden min-h-[44px] max-h-48"
            :class="postCreation ? 'bg-white' : 'hover:bg-gray-50 cursor-pointer'"
            rows="1"
            placeholder="What's on your mind?"
            @input="adjustHeight"
            @click="postCreation = !postCreation"
        ></textarea>
        <div class="flex justify-end gap-2" v-if="postCreation">
            <button type="button" class="rounded-md px-3 py-2 text-sm font-semibold text-gray-900 bg-gray-200 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-300 relative">
                Attach Files
                <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"/>
            </button>
            <button
                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                type="submit"
            >
                {{postForm.processing ? 'Posting...' : 'Post'}}
            </button>
        </div>
    </form>
</template>

<style scoped></style>
