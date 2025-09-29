<script setup>
import { ref } from "vue";
import {
    XMarkIcon,
    ArrowUturnLeftIcon,
} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
});
const deletedAttachmentIds = ref([]);
const emit = defineEmits(["update:modelValue", "deleteAttachment"]);
const attachments = ref(props.modelValue);

const isImage = (attachment) => {
    return (
        attachment.file?.type.startsWith("image/") || attachment.type === "png"
    );
};

const removeFile = (attachment, index) => {
    if (attachment.id) {
        attachment.deleted = true;
        const idExists = deletedAttachmentIds.value.some(
            (id) => id === attachment.id,
        );
        if (!idExists) deletedAttachmentIds.value.push(attachment.id);
        emit("deleteAttachment", deletedAttachmentIds.value);
    } else {
        attachments.value = attachments.value.filter((_, i) => i !== index);
        emit("update:modelValue", attachments.value);
    }
};

const revertRemoveFile = (attachment) => {
    attachment.deleted = false;
    deletedAttachmentIds.value = deletedAttachmentIds.value.filter(
        (id) => id !== attachment.id,
    );
    emit("deleteAttachment", deletedAttachmentIds.value);
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
            :key="index"
            class="relative group space-y-2"
        >
            <XMarkIcon
                v-if="!attachment.deleted"
                class="w-6 h-6 absolute p-1 top-4 right-2 cursor-pointer z-10 rounded-full bg-white opacity-75 hover:opacity-100 transition-all duration-200"
                @click="removeFile(attachment, index)"
            />
            <div
                v-if="attachment.deleted"
                class="absolute inset-0 bg-black/50 z-10 flex flex-col items-center justify-end text-white text-sm hover:bg-black/60 transition-all duration-200 cursor-pointer"
            >
                <ArrowUturnLeftIcon
                    class="w-6 h-6 absolute p-1 top-4 right-2 cursor-pointer z-10 rounded-full bg-white opacity-75 hover:opacity-100 transition-all duration-200 text-gray-600"
                    @click="revertRemoveFile(attachment)"
                />
                To be deleted
            </div>
            <template v-if="isImage(attachment)">
                <img
                    :src="attachment.url || attachment.path"
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
