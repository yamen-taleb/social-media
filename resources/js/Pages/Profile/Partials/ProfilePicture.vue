<script setup>
import { computed, ref } from 'vue'
import { PhotoIcon } from '@heroicons/vue/24/outline'
import { useToast } from 'vue-toastification'
import { useImageUpload } from '@/Composables/useImageUpload.js'
import { useForm } from '@inertiajs/vue3'
import { useResetImage } from '@/Composables/useResetImage.js'
import { useUpdateImage } from '@/Composables/useUpdateImage.js'

const props = defineProps({
  avatar: {
    type: String,
    default: 'https://cdn.iconscout.com/icon/free/png-256/free-avatar-370-456322.png?f=webp',
  },
})

const toast = useToast()
const showChangeImage = ref(false)
const avatarImage = ref('')

const imageForm = useForm({
  image: null,
})

const showImagePreview = computed(
  () =>
    avatarImage.value ||
    props.avatar ||
    'https://cdn.iconscout.com/icon/free/png-256/free-avatar-370-456322.png?f=webp'
)
const showEditControls = computed(() => avatarImage.value)

const handleImageChange = (event) => {
  useImageUpload(event, imageForm, avatarImage, toast)
  showChangeImage.value = true
}

const resetImage = () => {
  useResetImage(imageForm, avatarImage)
  showChangeImage.value = false
}

const updateAvatar = () => {
  useUpdateImage(imageForm, toast, 'profile.avatar')
  resetImage()
}
</script>

<template>
  <div
    class="relative -mt-[64px] ml-[48px] h-[128px] w-[128px] cursor-pointer"
    @click="showChangeImage = !showChangeImage"
  >
    <!-- Profile Picture -->
    <img
      :src="showImagePreview"
      class="h-full w-full rounded-full border-2 border-white object-cover"
      alt="Profile picture"
    />

    <!-- Overlay -->
    <div
      class="absolute inset-0 rounded-full bg-black bg-opacity-0 opacity-0 transition-all duration-300 hover:bg-opacity-50 hover:opacity-100"
    ></div>
    <div
      class="relative mt-3 rounded-md bg-white p-2 text-gray-600 shadow-md hover:text-gray-700"
      v-if="showChangeImage"
    >
      <div v-if="!showEditControls" class="flex items-center gap-2 hover:bg-gray-50">
        <PhotoIcon class="h-4 w-4" />
        <span class="text-xs"> Choose picture </span>
        <input
          type="file"
          accept="image/*"
          class="file-input-overlay"
          @change="handleImageChange"
        />
      </div>
      <div v-else class="flex items-center gap-3 text-xs">
        <button class="mx-auto text-red-500 hover:text-red-600" @click.stop="resetImage">
          Cancel
        </button>
        <span class="h-4 border-l border-gray-300"></span>
        <button class="mx-auto" @click.stop="updateAvatar">
          {{ imageForm.processing ? 'Saving...' : 'Save' }}
        </button>
      </div>
    </div>
  </div>
</template>
