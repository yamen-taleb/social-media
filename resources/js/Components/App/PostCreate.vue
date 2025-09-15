<script setup>
    import {ref} from "vue";

    const postCreation = ref(false);
</script>

<template>
    <div class="py-6 px-4 border rounded-md bg-white shadow-sm mb-3">
        <div class="py-4 px-3 text-gray-500 border rounded mb-4" @click="postCreation = !postCreation" :class="postCreation ? 'bg-gray-100' : 'hover:bg-gray-50 cursor-pointer'">
            Create Post Here!
        </div>
        <div class="flex justify-end gap-2" v-if="postCreation">
            <button type="button" class="rounded-md px-3 py-2 text-sm font-semibold text-gray-900 bg-gray-200 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-300 relative">
                Attach Files
                <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"/>
            </button>
            <button
                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
                Submit
            </button>
        </div>
    </div>
</template>

<style scoped></style>
