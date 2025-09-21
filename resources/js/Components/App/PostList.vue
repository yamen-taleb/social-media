<script setup>

import PostCreate from "@/Components/App/PostCreate.vue";
import PostItem from "@/Components/App/PostItem.vue";
import {usePage} from "@inertiajs/vue3";

const posts = usePage().props.posts.data
</script>

<template>
    <div>
        <PostCreate/>
        <div v-for="post in posts" :key="post.id">
            <PostItem :post="post"/>
        </div>
    </div>
</template>

<style scoped>

</style>
