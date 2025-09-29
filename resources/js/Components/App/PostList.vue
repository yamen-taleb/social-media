<script setup>
import PostCreate from "@/Components/App/PostCreate.vue";
import PostItem from "@/Components/App/PostItem.vue";
import { usePage } from "@inertiajs/vue3";
import PostEditModel from "@/Components/App/PostEditModel.vue";
import { inject, reactive, ref } from "vue";
import AttachmentsPreview from "@/Components/AttachmentsPreview.vue";

const props = defineProps({
    posts: {
        type: Array,
        required: true,
    },
});
const currentPost = ref({});
const showEditModel = ref(false);
const showAttachmentPreview = ref(false);
const attachmentsPreview = reactive({
    attachments: [],
    index: 0,
});

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

const showAttachmentPreviewModel = (attachments, index) => {
    showAttachmentPreview.value = true;
    attachmentsPreview.attachments = attachments;
    attachmentsPreview.index = index;
};
</script>

<template>
    <div>
        <PostCreate @createPost="openEditModel" />
        <div v-for="post in posts" :key="post.id">
            <PostItem
                :post="post"
                @editClick="openEditModel"
                @showAttachmentPreview="showAttachmentPreviewModel"
            />
        </div>
    </div>
    <PostEditModel
        v-model="showEditModel"
        :post="currentPost"
        @hide="currentPost = {}"
    />

    <AttachmentsPreview
        v-model="showAttachmentPreview"
        :attachments="attachmentsPreview.attachments"
        :index="attachmentsPreview.index"
        @update:index="attachmentsPreview.index = $event"
    />
</template>

<style scoped></style>
