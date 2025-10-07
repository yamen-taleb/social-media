<script setup>
import Photo from '@/Components/Icons/Photo.vue'
import { computed, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { XMarkIcon, CheckCircleIcon } from '@heroicons/vue/24/outline'
import { useToast } from 'vue-toastification'
import { useImageUpload } from '@/Composables/useImageUpload.js'
import { useResetImage } from '@/Composables/useResetImage.js'
import { useUpdateImage } from '@/Composables/useUpdateImage.js'

const props = defineProps({
  coverPath: {
    type: String,
    default: 'https://www.prodraw.net/fb_cover/images/fb_cover_65.jpg',
  },
})

const toast = useToast()
const coverImage = ref('')
const imageForm = useForm({
  image: null,
})

const showImagePreview = computed(() => coverImage.value || props.coverPath)
const showEditControls = computed(() => coverImage.value)

const handleImageChange = (event) => useImageUpload(event, imageForm, coverImage, toast)
const resetImage = () => useResetImage(imageForm, coverImage)

const updateCover = () => {
  useUpdateImage(imageForm, toast, 'profile.cover')
  resetImage()
}
</script>

<template>
  <div class="group/cover relative">
    <img :src="showImagePreview" alt="Cover photo" class="h-[200px] w-full object-cover" />
    <div
      v-if="!showEditControls"
      class="absolute right-1 top-1 flex cursor-pointer items-center gap-1 rounded bg-gray-200 p-1 text-gray-700 opacity-0 transition-all group-hover/cover:opacity-100"
    >
      <Photo />
      Edit cover
      <input
        type="file"
        class="absolute inset-0 z-10 cursor-pointer opacity-0"
        @change="handleImageChange"
      />
    </div>
    <div
      v-else
      class="absolute right-1 top-1 flex cursor-pointer items-center gap-1 rounded p-1 opacity-0 transition-all group-hover/cover:opacity-100"
    >
      <button
        class="flex items-center gap-1 rounded bg-red-500 p-2 text-white hover:bg-red-600"
        @click="resetImage"
      >
        <XMarkIcon class="h-5 w-5" />
        Cancel
      </button>
      <button
        class="flex items-center gap-1 rounded bg-gray-900 p-2 text-white hover:bg-gray-800"
        @click="updateCover"
        :disabled="imageForm.processing"
      >
        <CheckCircleIcon class="h-5 w-5" />
        {{ imageForm.processing ? 'Saving...' : 'Save' }}
      </button>
    </div>
  </div>
</template>

<style scoped></style>
