<script setup>
import {computed, watch} from 'vue'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import AutoResizeTextarea from "@/Components/App/AutoResizeTextarea.vue";
import PostUserHeader from "@/Components/App/PostUserHeader.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import RichEditor from "@/Components/App/RichEditor.vue";

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
    modelValue: Boolean
})

const toast = useToast()
const postForm = useForm({
    title: null,
    description: props.post.description,
    attachments: null,
    group_id: null,
})

const emit = defineEmits(['update:modelValue'])

const show =  computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
})

const closeModal = () => show.value = false
const submit = () => {
    if (props.post.is_new)
        submitPost()
    else
        updatePost()
}
const getOptions = (isNew = false) => ({
    preserveScroll: true,
    onSuccess: () => {
        toast.success(`Post ${isNew ? 'created' : 'updated'} successfully`);
        show.value = false;
        postForm.reset();
    },
    onError: (e) => {
        if (typeof e === 'object') {
            const firstErrorKey = Object.keys(e)[0];
            toast.error(e[firstErrorKey]);
        } else {
            toast.error(`Post failed to ${isNew ? 'create' : 'update'}`);
        }
    }
});
const updatePost = () => {
   postForm.put(route('posts.update', {
       id: props.post.id
   }), getOptions(false))
}

const submitPost = () => {
    postForm.post(route('posts.store'), getOptions(true));
}
watch(() => props.post, (newValue, oldValue) => {
   postForm.description = newValue.description;
})
</script>

<template>
    <teleport to="body">
        <TransitionRoot appear :show="show" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-10">
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
                    <div
                        class="flex min-h-full items-center justify-center p-4 text-center"
                    >
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
                                <DialogTitle
                                    as="h3"
                                    class="text-lg font-medium leading-6 text-gray-800"
                                >
                                    {{post.is_new ? 'Create Post' : 'Update Post'}}
                                </DialogTitle>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500">
                                        <PostUserHeader
                                            :post
                                            :show-time="false"
                                            class="mb-3"
                                        />
                                        <RichEditor v-model="postForm.description" />
                                        <!--                                        <AutoResizeTextarea v-model="postForm.description"/>-->
                                    </p>
                                </div>

                                <div class="mt-4 flex gap-2 items-center justify-end">
                                    <button
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
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

