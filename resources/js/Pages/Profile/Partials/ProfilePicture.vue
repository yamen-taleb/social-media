<script setup>
import { computed, ref } from 'vue'
import { PhotoIcon } from '@heroicons/vue/24/outline'
import { useForm } from '@inertiajs/vue3'
import { useResetImage } from '@/Composables/useResetImage.js'
import { useImageUpload } from '@/Composables/useImageUpload.js'

const props = defineProps({
  avatar: {
    type: String,
    default: 'https://cdn.iconscout.com/icon/free/png-256/free-avatar-370-456322.png?f=webp',
  },
  allowedToUpdated: {
    type: Boolean,
    required: true,
  },
})

const showChangeImage = ref(false)
const avatarImage = ref('')

const emit = defineEmits(['update'])

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
  useImageUpload(event, imageForm, avatarImage)
  showChangeImage.value = true
}

const resetImage = () => {
  useResetImage(imageForm, avatarImage)
  showChangeImage.value = false
}

const updateAvatar = () => {
  emit('update', imageForm)
  // useUpdateImage(imageForm, 'profile.avatar')
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
      alt="Profile picture"
      class="h-full w-full rounded-full border-2 border-white object-cover"
    />

    <!-- Overlay -->
    <div
      v-if="allowedToUpdated"
      class="absolute inset-0 rounded-full bg-black bg-opacity-0 opacity-0 transition-all duration-300 hover:bg-opacity-50 hover:opacity-100"
    ></div>
    <div
      v-if="showChangeImage && allowedToUpdated"
      class="relative mt-3 rounded-md bg-white p-2 text-gray-600 shadow-md hover:text-gray-700 dark:bg-gray-800 dark:text-gray-200"
    >
      <div
        v-if="!showEditControls && allowedToUpdated"
        class="flex cursor-pointer items-center gap-2 hover:bg-gray-50 dark:hover:bg-gray-800"
      >
        <PhotoIcon class="h-4 w-4" />
        <span class="text-xs"> Choose picture </span>
        <input
          accept="image/*"
          class="file-input-overlay"
          type="file"
          @change="handleImageChange"
        />
      </div>
      <div v-else-if="allowedToUpdated" class="flex items-center gap-3 text-xs">
        <button
          class="mx-auto text-red-500 transition-colors hover:text-red-600 dark:text-red-600 dark:hover:text-red-500"
          @click.stop="resetImage"
        >
          Cancel
        </button>
        <span class="h-4 border-l border-gray-300 dark:text-gray-200"></span>
        <button
          class="mx-auto transition-colors dark:text-gray-200 dark:hover:text-white"
          @click.stop="updateAvatar"
        >
          {{ imageForm.processing ? 'Saving...' : 'Save' }}
        </button>
      </div>
    </div>
  </div>
</template>
