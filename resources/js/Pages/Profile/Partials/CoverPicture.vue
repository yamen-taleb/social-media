<script setup>

import Photo from "@/Components/Icons/Photo.vue";
import {computed, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import { XMarkIcon, CheckCircleIcon } from '@heroicons/vue/24/outline'
import {useToast} from "vue-toastification";
import {useImageUpload} from "@/Composables/useImageUpload.js"
import {useResetImage} from "@/Composables/useResetImage.js";
import {useUpdateImage} from "@/Composables/useUpdateImage.js";

const props = defineProps({
    coverPath: {
        type: String,
        default: 'https://www.prodraw.net/fb_cover/images/fb_cover_65.jpg'
    }
});

const toast = useToast();
const coverImage = ref('');
const imageForm = useForm({
    image: null,
})

const showImagePreview = computed(() => coverImage.value || props.coverPath);
const showEditControls = computed(() => coverImage.value);

const handleImageChange = (event) => useImageUpload(event, imageForm, coverImage, toast)
const resetImage = () => useResetImage(imageForm, coverImage)

const updateCover = () => {
    useUpdateImage(imageForm, toast, 'profile.cover')
    resetImage()
}

</script>

<template>
    <div class="relative group/cover">
        <img
            :src="showImagePreview"
            alt="Cover photo"
            class="w-full h-[200px] object-cover"
        />
        <div v-if="!showEditControls" class="flex items-center gap-1 absolute right-1 top-1 p-1 text-gray-700 bg-gray-200 rounded cursor-pointer opacity-0 group-hover/cover:opacity-100 transition-all">
            <Photo/>
            Edit cover
            <input type="file" class="absolute inset-0 opacity-0 cursor-pointer z-10" @change="handleImageChange">
        </div>
        <div v-else class="flex items-center gap-1 absolute right-1 top-1 p-1 rounded cursor-pointer opacity-0 group-hover/cover:opacity-100 transition-all">
            <button class="flex items-center gap-1 text-white bg-red-500 hover:bg-red-600 p-2 rounded" @click="resetImage">
                <XMarkIcon class="w-5 h-5"/>
                Cancel
            </button>
            <button class="flex items-center gap-1 p-2 text-white bg-gray-900 hover:bg-gray-800 rounded" @click="updateCover" :disabled="imageForm.processing">
                <CheckCircleIcon class="w-5 h-5"/>
                {{ imageForm.processing ? 'Saving...' : 'Save' }}
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
