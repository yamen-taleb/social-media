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
</style>
