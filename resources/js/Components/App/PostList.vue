<script setup>
import PostCreate from "@/Components/App/PostCreate.vue";
import PostItem from "@/Components/App/PostItem.vue";
import { usePage } from "@inertiajs/vue3";
import PostEditModel from "@/Components/App/PostEditModel.vue";
import { inject, ref } from "vue";

const props = defineProps({
    posts: {
        type: Array,
        required: true,
    },
});
const currentPost = ref({});
const showEditModel = ref(false);

const openEditModel = (post = null) => {
    if (post) {
        // If a post is provided, use it for editing
        currentPost.value = post;
    } else {
        // If no post is provided, create a new empty post
        currentPost.value = {
            id: null,
            title: "",
            description: "",
            attachments: null,
            group_id: null,
            is_new: true,
        };
    }
    showEditModel.value = true;
};
</script>

<template>
    <div>
        <PostCreate @createPost="openEditModel" />
        <div v-for="post in posts" :key="post.id">
            <PostItem :post="post" @editClick="openEditModel" />
        </div>
    </div>
    <PostEditModel
        v-model="showEditModel"
        :post="currentPost"
        @hide="currentPost = {}"
    />
</template>

<style scoped></style>
