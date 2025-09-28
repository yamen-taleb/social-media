<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import Download from "@/Components/Icons/Download.vue";
import Like from "@/Components/Icons/Like.vue";
import Comment from "@/Components/Icons/Comment.vue";
import PostMenu from "@/Components/App/PostMenu.vue";
import {Link} from "@inertiajs/vue3";
import PostUserHeader from "@/Components/App/PostUserHeader.vue";

const props = defineProps({
    post: Object
});

const isImage = (attachment) => {
    return attachment.type.startsWith('image/')
}

const emit = defineEmits(['editClick'])
const openEditModel = () => {
    emit('editClick', props.post)
}
</script>

<template>
    <div class="shadow-sm rounded-md bg-white mb-6 py-4 px-3 border">
        <div class="flex items-center justify-between py-4 px-2">
            <PostUserHeader :post/>
            <PostMenu :post="post" @edit="openEditModel"/>
        </div>
        <!-- Rest of your template remains the same -->
        <div class="mb-3 ck-content-output">
            <Disclosure v-slot="{ open }">
                <div v-html="post.description?.substring(0, 200)" v-if="!open"/>
                <DisclosurePanel>
                    <div v-html="post.description"/>
                </DisclosurePanel>

                <div class="flex justify-end" v-if="post.description?.length > 200">
                    <DisclosureButton class="text-blue-500 hover:text-blue-600 hover:underline">
                        <span v-if="!open">Read more</span>
                        <span v-else>Show less</span>
                    </DisclosureButton>
                </div>
            </Disclosure>
        </div>
        <div class="grid grid-cols-2 gap-2" v-if="post.attachments?.length">
            <div v-for="attachment in post.attachments" :key="attachment.id" class="relative group mb-3">
                <div class="absolute top-2 right-2 bg-gray-700 rounded p-1 shadow hover:bg-gray-800 text-white opacity-0 group-hover:opacity-100 transition-all">
                    <Download/>
                </div>
                <template v-if="isImage(attachment)">
                    <img :src="attachment.url" alt="" class="w-full rounded-md object-cover aspect-square"/>
                </template>
                <template v-else>
                    <div class="flex items-center justify-center w-full bg-gray-100 border rounded-md aspect-square">
                        <span class="text-gray-500">{{ attachment.name || 'File' }}</span>
                    </div>
                </template>
            </div>
        </div>
        <div class="flex gap-2">
            <Like/>
            <Comment/>
        </div>
    </div>
</template>

<style scoped>

</style>
