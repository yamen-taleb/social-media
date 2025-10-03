<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    user: {
        type: [null, Object],
        required: true,
    },
    showTime: {
        type: Boolean,
        default: true,
    },
    group: {
        type: [null, Object],
    },
    created_at: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <div class="flex gap-3 items-center">
        <Link
            :href="
                route('profile', {
                    username: user.username,
                })
            "
        >
            <img
                class="w-14 h-14 rounded-full border border-2 hover:border-blue-700 transition-all object-cover"
                :src="user.avatar"
                alt=""
            />
        </Link>
        <div>
            <h3 class="text-gray-600 font-semibold flex items-center gap-1">
                <Link
                    class="hover:text-gray-800 hover:underline"
                    :href="
                        route('profile', {
                            username: user.username,
                        })
                    "
                >
                    {{ user.name }}
                </Link>
                <template v-if="group?.name">
                    >
                    <a href="javascript:void(0)">
                        <h3 class="hover:text-gray-800 hover:underline">
                            {{ group.name }}
                        </h3>
                    </a>
                </template>
            </h3>
            <small v-show="showTime" class="text-gray-400">
                {{ created_at }}
            </small>
        </div>
    </div>
</template>

<style scoped></style>
