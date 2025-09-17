<script setup>

import Photo from "@/Components/Icons/Photo.vue";
import {computed, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import { XMarkIcon, CheckCircleIcon } from '@heroicons/vue/24/outline'
import {useToast} from "vue-toastification";

const props = defineProps({
    coverPath: {
        type: String,
        default: 'https://www.prodraw.net/fb_cover/images/fb_cover_65.jpg'
    }
});

const toast = useToast();
const coverImage = ref('');
const imageForm = useForm({
    cover: null,
})

const showImagePreview = computed(() => coverImage.value || props.coverPath);
const showEditControls = computed(() => coverImage.value);

const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        toast.error('Please select a valid image file');
        return;
    }

    imageForm.cover = file;
    const reader = new FileReader();

    reader.onload = () => {
        coverImage.value = reader.result;
    };
    reader.onerror = () => {
        toast.error('Error reading image file');
    };

    reader.readAsDataURL(file);
};

const resetImage = () => {
    coverImage.value = '';
    imageForm.reset();
};

const updateCover = () => {
    if (!imageForm.cover) {
        toast.error('No image selected');
        return;
    }

    imageForm.post(route('profile.cover'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Cover updated successfully');
            resetImage();
        },
        onError: (errors) => {
            const errorMessage = errors.cover || 'Failed to update cover';
            toast.error(errorMessage);
            resetImage();
        }
    });
};

</script>

<template>
    <img
        :src="showImagePreview"
        alt="Cover photo"
        class="w-full h-[200px] object-cover"
    />
    <div v-if="!showEditControls" class="flex items-center gap-1 absolute right-1 top-1 p-1 text-gray-700 bg-gray-200 rounded cursor-pointer opacity-0 group-hover:opacity-100 transition-all">
        <Photo/>
        Edit cover
        <input type="file" class="absolute inset-0 opacity-0 cursor-pointer z-10" @change="handleImageChange">
    </div>
    <div v-else class="flex items-center gap-1 absolute right-1 top-1 p-1 rounded cursor-pointer opacity-0 group-hover:opacity-100 transition-all">
       <button class="flex items-center gap-1 text-white bg-red-500 hover:bg-red-600 p-2 rounded" @click="resetImage">
           <XMarkIcon class="w-5 h-5"/>
           Cancel
       </button>
        <button class="flex items-center gap-1 p-2 text-white bg-gray-900 hover:bg-gray-800 rounded" @click="updateCover" :disabled="imageForm.processing">
            <CheckCircleIcon class="w-5 h-5"/>
            {{ imageForm.processing ? 'Saving...' : 'Save' }}
        </button>
    </div>
</template>

<style scoped>

</style>
