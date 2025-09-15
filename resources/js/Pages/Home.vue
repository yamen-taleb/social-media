<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GroupList from "@/Components/App/GroupList.vue";
import FollowingList from "@/Components/App/FollowingList.vue";
import PostList from "@/Components/App/PostList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
</script>

<template>
    <Head title="Social Media" />
    <AuthenticatedLayout>
        <div class="grid lg:grid-cols-12 gap-3 py-3 px-1">
            <div class="lg:col-span-3 hidden lg:block">
                <GroupList/>
            </div>
            <div class="lg:col-span-6">
                <PostList/>
            </div>
            <div class="lg:col-span-3 hidden lg:block">
                <FollowingList/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
