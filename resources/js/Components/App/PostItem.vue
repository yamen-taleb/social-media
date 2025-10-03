<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import Download from "@/Components/Icons/Download.vue";
import Like from "@/Components/Icons/Like.vue";
import Comment from "@/Components/Icons/Comment.vue";
import PostUserHeader from "@/Components/App/PostUserHeader.vue";
import axiosClient from "@/axiosClient.js";
import { router, usePage } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import ShowLessReadMore from "@/Components/App/ShowLessReadMore.vue";
import EditDeleteMenu from "@/Components/App/EditDeleteMenu.vue";

const props = defineProps({
    post: Object,
});

const isImage = (attachment) => {
    return attachment.type.startsWith("image/");
};

const emit = defineEmits([
    "editClick",
    "showAttachmentPreview",
    "showPostReview",
]);
const openEditModel = () => {
    emit("editClick", props.post);
};

const showAttachmentPreview = (attachments, index) => {
    emit("showAttachmentPreview", attachments, index);
};

const likePost = (postId) => {
    axiosClient
        .post(route("reactions.react", postId), {
            type: "like",
        })
        .then(({ data }) => {
            props.post.current_user_has_reaction =
                data.current_user_has_reaction;
            props.post.num_of_reactions = data.num_of_reactions;
        })
        .catch((error) => {
            console.log(error);
        });
};

const deletePost = () => {
    if (window.confirm("Are you sure you want to delete this post?")) {
        router.delete(route("posts.destroy", props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                useToast().success("Post deleted successfully");
            },
            onError: () => {
                useToast().error("Post deleted failed");
            },
        });
    }
};
</script>

<template>
    <div class="shadow-sm rounded-md bg-white mb-6 py-4 px-3 border">
        <div class="flex items-center justify-between py-4 px-2">
            <PostUserHeader
                :user="post.user"
                :created_at="post.created_at"
                :group="post.group"
            />
            <EditDeleteMenu
                v-if="post.user.id === usePage().props.auth.user.id"
                @edit="openEditModel"
                @delete="deletePost"
            />
        </div>
        <!-- Rest of your template remains the same -->
        <div class="mb-3 ck-content-output">
            <ShowLessReadMore :content="post.description" />
        </div>
        <div
            class="grid gap-2"
            :class="[
                post.attachments?.length === 1 ? 'grid-cols-1' : 'grid-cols-2',
            ]"
            v-if="post.attachments?.length"
        >
            <div
                v-for="(attachment, index) in post.attachments?.slice(0, 4)"
                :key="attachment.id"
                class="relative group mb-3"
            >
                <a
                    :href="route('post-attachments.download', attachment)"
                    v-if="!(index === 3 && post.attachments.length > 4)"
                    class="absolute top-2 right-2 bg-gray-700 rounded p-1 shadow hover:bg-gray-800 text-white opacity-0 group-hover:opacity-100 transition-all"
                >
                    <Download />
                </a>
                <div
                    v-if="index === 3 && post.attachments.length > 4"
                    class="absolute inset-0 bg-black/50 z-10 flex items-center justify-center text-white text-2xl hover:bg-black/60 transition-all duration-200 cursor-pointer"
                >
                    +{{ post.attachments.length - 4 }}
                </div>
                <template
                    v-if="isImage(attachment) || attachment.type === 'png'"
                >
                    <img
                        @click="showAttachmentPreview(post.attachments, index)"
                        :src="attachment.path"
                        alt=""
                        class="w-full rounded-md aspect-square"
                        :class="[
                            post.attachments?.length === 1
                                ? 'object-contain max-h-72'
                                : 'object-cover',
                        ]"
                    />
                </template>
                <template v-else>
                    <div
                        class="flex items-center justify-center w-full bg-gray-100 border rounded-md aspect-square"
                    >
                        <span class="text-gray-500">{{
                            attachment.name || "File"
                        }}</span>
                    </div>
                </template>
            </div>
        </div>
        <div class="flex flex-col gap-2 mt-1">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center text-sm text-gray-500 ml-1">
                    <span v-if="post.num_of_reactions > 0">
                        {{ post.num_of_reactions }}
                        {{ post.num_of_reactions === 1 ? "like" : "likes" }}
                    </span>
                </div>
                <div
                    v-if="post.num_of_comments > 0"
                    class="flex items-center text-sm text-gray-500 ml-1 cursor-pointer hover:text-gray-700 hover:underline"
                    @click="emit('showPostReview', post)"
                >
                    <span>
                        {{ post.num_of_comments }}
                        {{
                            post.num_of_comments === 1 ? "Comment" : "Comments"
                        }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <Like
                    @click="likePost(post.id)"
                    :hasReaction="post.current_user_has_reaction"
                />
                <Comment @click="emit('showPostReview', post)" />
            </div>
        </div>
    </div>
</template>

<style scoped></style>
