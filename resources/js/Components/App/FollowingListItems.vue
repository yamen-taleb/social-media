<script setup>

import FollowingItem from "@/Components/App/FollowingItem.vue";
import TextInput from "@/Components/TextInput.vue";
import {ref} from "vue";

const searchKeyword = ref('')
</script>

<template>
    <div class="py-6 overflow-y-auto scrollbar-thin">
        <div class="text-gray-400 font-semibold text-center" v-if="false">
            You don't have friends yet!
        </div>
        <div v-else>
            <TextInput class="mb-2" :model-value="searchKeyword" placeholder="Type to search"/>
            <FollowingItem  image="https://picsum.photos/100"
                            name="Laravel Developers"/>
            <FollowingItem  image="https://picsum.photos/100"
                            name="Vue Developers"/>
            <FollowingItem  image="https://picsum.photos/100"
                            name="Laravel Developers"/>
            <FollowingItem  image="https://picsum.photos/100"
                            name="Vue Developers"/>
        </div>
    </div>
</template>

<style scoped>
.scrollbar-thin::-webkit-scrollbar {
    width: 7px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background-color: #d1d5db; /* gray-300 */
    border-radius: 4px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background-color: #9ca3af; /* gray-400 */
}

.scrollbar-thin::-webkit-scrollbar-track {
    background-color: #f3f4f6; /* gray-100 */
}
</style>
