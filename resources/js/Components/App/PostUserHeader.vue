<script setup>

import {Link, usePage} from "@inertiajs/vue3";

const authUser = usePage().props.auth.user
defineProps({
    post: {
        type: Object,
        required: true,
    },
    showTime: {
        type: Boolean,
        default: true,
    }
})
</script>

<template>
    <div class="flex gap-3 items-center">
        <Link
            :href="route('profile', {
                username: authUser.username
            })"
        >
            <img class="w-14 h-14 rounded-full border border-2 hover:border-blue-700 transition-all object-cover" :src="authUser.avatar" alt="">
        </Link>
        <div>
            <h3 class="text-gray-600 font-semibold flex items-center gap-1">
                <Link
                    class="hover:text-gray-800 hover:underline"
                    :href="route('profile', {
                            username: authUser.username
                        })"
                >
                    {{ authUser.name }}
                </Link>
                <template v-if="post.group?.name">
                    >
                    <a href="javascript:void(0)" >
                        <h3 class="hover:text-gray-800 hover:underline">{{ post.group.name }}</h3>
                    </a>
                </template>
            </h3>
            <small
                v-show="showTime"
                class="text-gray-400"
            >
                {{ post.created_at }}
            </small>
        </div>
    </div>
</template>

<style scoped>

</style>
