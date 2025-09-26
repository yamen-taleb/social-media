<script setup>

import PostCreate from "@/Components/App/PostCreate.vue";
import PostItem from "@/Components/App/PostItem.vue";
import {usePage} from "@inertiajs/vue3";
import PostEditModel from "@/Components/App/PostEditModel.vue";
import {inject, ref} from "vue";

const props = defineProps({
   posts: {
       type: Array,
       required: true,
   }
})
const currentPost = ref({})
const showEditModel = ref(false)

const openEditModel = (post) => {
   currentPost.value = post
   showEditModel.value = true
}

</script>

<template>
    <div>
        <PostCreate/>
        <div v-for="post in posts" :key="post.id">
            <PostItem :post="post" @editClick="openEditModel"/>
        </div>
    </div>
    <PostEditModel v-model="showEditModel" :post="currentPost"/>
</template>

<style scoped>

</style>
