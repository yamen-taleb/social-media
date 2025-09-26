<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import AutoResizeTextarea from './AutoResizeTextarea.vue';

const postCreation = ref(false);
const toast = useToast();

const postForm = useForm({
    title: null,
    description: null,
    attachments: null,
    group_id: null,
})

const submitPost = () => {
    postForm.post(route('posts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Post created successfully');
            postForm.reset();
            postCreation.value = false;
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
        <AutoResizeTextarea
            v-model="postForm.description"
            :placeholder="'What\'s on your mind?'"
            :is-active="postCreation"
            @click="postCreation = true"
            class="w-full"
        />
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
