<script setup>
import { ref } from "vue";
import { XMarkIcon } from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
});
const emit = defineEmits(["update:modelValue"]);
const attachments = ref(props.modelValue);
const isImage = (attachment) => {
    return attachment.type.startsWith("image/");
};

const removeFile = (index) => {
    attachments.value = attachments.value.filter((_, i) => i !== index);
    emit("update:modelValue", attachments.value);
};
</script>

<template>
    <div
        class="grid gap-2 mt-2 bg-gray-100 p-1 rounded relative"
        :class="[attachments?.length === 1 ? 'grid-cols-1' : 'grid-cols-2']"
        v-if="attachments?.length"
    >
        <div
            v-for="(attachment, index) in attachments"
            :key="attachment.id"
            class="relative group space-y-2"
        >
            <XMarkIcon
                class="w-6 h-6 absolute p-1 top-4 right-2 cursor-pointer z-10 rounded-full bg-white opacity-75 hover:opacity-100 transition-all duration-200"
                @click="removeFile(index)"
            />
            <template v-if="isImage(attachment.file)">
                <img
                    :src="attachment.url"
                    alt=""
                    class="w-full rounded-md aspect-square"
                    :class="[
                        attachments?.length === 1
                            ? 'object-contain'
                            : 'object-cover',
                    ]"
                />
            </template>
            <template v-else>
                <div
                    class="flex items-center justify-center w-full bg-gray-100 border rounded-md aspect-square"
                >
                    <span class="text-gray-500">{{
                        attachment.name || "File"
                    }}</span>
                </div>
            </template>
        </div>
    </div>
</template>

<style scoped></style>
