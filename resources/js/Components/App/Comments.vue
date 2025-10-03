<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import {
    ChatBubbleOvalLeftEllipsisIcon,
    PaperAirplaneIcon,
} from "@heroicons/vue/24/outline/index.js";
import ShowLessReadMore from "@/Components/App/ShowLessReadMore.vue";
import EditDeleteMenu from "@/Components/App/EditDeleteMenu.vue";
import { useToast } from "vue-toastification";
import AutoResizeTextarea from "@/Components/App/AutoResizeTextarea.vue";
import { ref } from "vue";

const props = defineProps({
    comments: {
        type: Array,
        default: [],
    },
});

const emit = defineEmits(["editComments", "update:comments"]);
const currentComment = ref({});

const deleteComment = (comment) => {
    if (window.confirm("Are you sure you want to delete this comment?"))
        router.delete(route("comments.destroy", comment.id), {
            preserveScroll: true,
            onSuccess: () => {
                props.comments.splice(props.comments.indexOf(comment), 1);
                useToast().success("Comment deleted");
            },
            onError: () => {
                useToast().error("Comment has not been deleted");
            },
        });
};
const editComment = (comment) => {
    currentComment.value = { ...comment };
};

const updateComment = () => {
    router.put(
        route("comments.update", currentComment.value.id),
        {
            body: currentComment.value.body,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                useToast().success("Comment updated");
                const updatedComments = props.comments.map((comment) => {
                    if (currentComment.value.id === comment.id) {
                        return {
                            ...comment,
                            body: currentComment.value.body,
                        };
                    }
                    return comment;
                });
                emit("update:comments", updatedComments);
                currentComment.value = {};
            },
            onError: (e) => {
                useToast().error(e.body);
            },
        },
    );
};
</script>

<template>
    <div class="space-y-4 px-4 mt-3">
        <div
            v-if="comments.length > 0"
            v-for="(comment, index) in comments"
            :key="index"
            class="group relative"
        >
            <div class="flex items-start justify-between">
                <div class="flex flex-1 gap-3 items-start">
                    <Link
                        :href="
                            route('profile', {
                                username: comment.user.username,
                            })
                        "
                        class="flex-shrink-0"
                    >
                        <img
                            class="w-10 h-10 rounded-full border-2 border-white shadow-sm object-cover"
                            :src="comment.user.avatar"
                            :alt="comment.user.name + ' profile picture'"
                        />
                    </Link>

                    <div class="flex-1 min-w-0">
                        <div
                            v-if="currentComment?.id === comment.id"
                            class="relative flex flex-col max-w-full"
                        >
                            <AutoResizeTextarea
                                v-model="currentComment.body"
                                :autofocus="true"
                                :is-active="true"
                            />
                            <button
                                type="button"
                                @click="updateComment"
                                :disabled="currentComment.body.trim() === ''"
                                class="absolute z-20 right-3 top-1/3 -translate-y-1/3"
                            >
                                <PaperAirplaneIcon
                                    class="h-5 w-5"
                                    :class="
                                        currentComment.body.trim() === ''
                                            ? 'cursor-not-allowed'
                                            : 'cursor-pointer'
                                    "
                                />
                            </button>
                            <span
                                class="text-blue-600 hover:text-blue-700 cursor-pointer text-sm"
                                @click="currentComment = {}"
                            >
                                cancel
                            </span>
                        </div>
                        <div
                            v-else
                            class="bg-gray-100 px-4 py-2 rounded-2xl rounded-tl-none inline-block"
                        >
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

                            <ShowLessReadMore
                                :content="comment.body"
                                class="text-gray-800 text-sm mt-0.5"
                            />
                        </div>

                        <div class="flex items-center mt-1 ml-2 space-x-3">
                            <span class="text-xs text-gray-500">
                                {{ comment.time_ago }}
                            </span>
                            <button
                                class="text-xs text-gray-500 hover:text-gray-700"
                            >
                                Like
                            </button>
                            <button
                                class="text-xs text-gray-500 hover:text-gray-700"
                            >
                                Reply
                            </button>
                        </div>
                    </div>
                </div>
                <EditDeleteMenu
                    v-if="
                        usePage().props.auth.user.id === comment.user.id &&
                        currentComment?.id !== comment.id
                    "
                    class="opacity-0 group-hover:opacity-100 transition-all duration-200"
                    @delete="deleteComment(comment)"
                    @edit="editComment(comment)"
                />
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-else
            class="flex flex-col items-center justify-center py-8 text-center"
        >
            <div
                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3"
            >
                <ChatBubbleOvalLeftEllipsisIcon class="w-6 h-6 text-gray-400" />
            </div>
            <p class="text-gray-600 font-medium">No comments yet</p>
            <p class="text-sm text-gray-500 mt-1">Be the first to comment</p>
        </div>
    </div>
</template>

<style scoped></style>
