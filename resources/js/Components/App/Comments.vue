<script setup>
import { Link } from "@inertiajs/vue3";
import { ChatBubbleOvalLeftEllipsisIcon } from "@heroicons/vue/24/outline/index.js";

defineProps({
    comments: {
        type: Array,
        default: [],
    },
});
</script>

<template>
    <div class="space-y-4 px-4 mt-3">
        <div
            v-if="comments.length > 0"
            v-for="(comment, index) in comments"
            :key="index"
            class="group relative"
        >
            <div class="flex gap-3 items-start">
                <Link
                    :href="
                        route('profile', { username: comment.user.username })
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

                        <!-- Comment Text -->
                        <p
                            class="text-gray-800 text-sm mt-0.5"
                            v-html="comment.body"
                        ></p>
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
