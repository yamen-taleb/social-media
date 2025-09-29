<script setup>
import { computed, ref, watch } from "vue";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import PostUserHeader from "@/Components/App/PostUserHeader.vue";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import { PaperClipIcon } from "@heroicons/vue/24/outline";
import RichEditor from "@/Components/App/RichEditor.vue";
import PostUploadedImage from "@/Components/App/PostUploadedImage.vue";

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
    modelValue: Boolean,
});

const attachments = ref([]);
const toast = useToast();
const postForm = useForm({
    title: null,
    description: props.post.description,
    attachments: [],
    group_id: null,
});

const emit = defineEmits(["update:modelValue"]);

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const closeModal = () => {
    show.value = false;
    postForm.reset();
    attachments.value = [];
};
const submit = () => {
    postForm.attachments = attachments.value?.map(
        (attachment) => attachment.file,
    );
    if (props.post.is_new) submitPost();
    else updatePost();
};
const getOptions = (isNew = false) => ({
    preserveScroll: true,
    onSuccess: () => {
        toast.success(`Post ${isNew ? "created" : "updated"} successfully`);
        closeModal();
    },
    onError: (e) => {
        if (typeof e === "object") {
            const firstErrorKey = Object.keys(e)[0];
            toast.error(e[firstErrorKey]);
        } else {
            toast.error(`Post failed to ${isNew ? "create" : "update"}`);
        }
    },
});
const updatePost = () => {
    postForm.put(
        route("posts.update", {
            id: props.post.id,
        }),
        getOptions(false),
    );
};

const submitPost = () => {
    postForm.post(route("posts.store"), getOptions(true));
};
watch(
    () => props.post,
    (newValue) => {
        postForm.description = newValue.description;
    },
);

const handleFileChange = async (e) => {
    for (const file of e.target.files) {
        const fileExists = attachments.value.some(
            (attachment) =>
                attachment.file.name === file.name &&
                attachment.file.size === file.size,
        );
        if (!fileExists) {
            const myFile = {
                file,
                url: await readFile(file),
            };
            attachments.value.push(myFile);
        }
    }
    e.target.value = null;
};

async function readFile(file) {
    return new Promise((res, rej) => {
        if (isImage(file)) {
            const reader = new FileReader();
            reader.onload = () => {
                res(reader.result);
            };
            reader.onerror = rej;
            reader.readAsDataURL(file);
        } else {
            res(null);
        }
    });
}

const isImage = (attachment) => {
    return attachment.type.startsWith("image/");
};
</script>

<template>
    <teleport to="body">
        <TransitionRoot appear :show="show" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-10">
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
                                    {{
                                        post.is_new
                                            ? "Create Post"
                                            : "Update Post"
                                    }}
                                </DialogTitle>
                                <div class="mt-4">
                                    <div class="text-sm text-gray-500">
                                        <div
                                            class="flex justify-between items-center"
                                        >
                                            <PostUserHeader
                                                :post
                                                :show-time="false"
                                                class="mb-3"
                                            />
                                            <button
                                                type="button"
                                                class="rounded-md px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 shadow-md hover:from-indigo-600 hover:to-purple-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition-all duration-300 relative"
                                                title="Select files"
                                            >
                                                <PaperClipIcon
                                                    class="size-6 text-white"
                                                />
                                                <input
                                                    type="file"
                                                    multiple
                                                    @change="handleFileChange"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                />
                                            </button>
                                        </div>
                                        <RichEditor
                                            v-model="postForm.description"
                                            autofocus
                                        />
                                        <PostUploadedImage
                                            v-model="attachments"
                                        />
                                    </div>
                                </div>

                                <div
                                    class="mt-4 flex gap-2 items-center justify-end"
                                >
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
