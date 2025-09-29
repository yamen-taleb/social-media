<script setup>
import { computed, onUnmounted, ref, watch, watchEffect } from "vue";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
} from "@headlessui/vue";
import {
    ChevronLeftIcon,
    ChevronRightIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    attachments: {
        type: Array,
        default: () => [],
    },
    index: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue", "update:index"]);
const localIndex = ref(props.index);
const localAttachments = ref(props.attachments);

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

function closeModal() {
    show.value = false;
    emit("update:index", localIndex.value);
}

function next() {
    if (localIndex.value < localAttachments.value.length - 1) {
        localIndex.value++;
    }
}

function prev() {
    if (localIndex.value > 0) {
        localIndex.value--;
    }
}

watchEffect(() => {
    if (Array.isArray(props.attachments)) {
        localAttachments.value = [...props.attachments];
    }
    if (typeof props.index === "number") {
        localIndex.value = props.index;
    }
});

onUnmounted(() => {
    localIndex.value = 0;
    localAttachments.value = [];
});

const handleKeydown = (e) => {
    if (!props.modelValue) return;

    switch (e.key) {
        case "ArrowLeft":
            prev();
            break;
        case "ArrowRight":
            next();
            break;
        case "Escape":
            closeModal();
            break;
    }
};
</script>

<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog
            as="div"
            @close="closeModal"
            @keydown.esc="closeModal"
            class="relative z-10"
            @keydown="handleKeydown"
        >
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center"
                >
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            ref="dialogPanel"
                            class="w-full h-[calc(100vh-4rem)] transform overflow-hidden relative rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                            tabindex="0"
                        >
                            <ChevronLeftIcon
                                class="h-14 w-14 absolute top-1/2 -translate-y-1/2 left-4 cursor-pointer text-gray-500 hover:text-gray-700 transition-colors duration-200 z-10"
                                :class="
                                    localAttachments.length === 1
                                        ? 'opacity-50 cursor-not-allowed'
                                        : 'cursor-pointer'
                                "
                                @click="prev"
                                tabindex="0"
                                aria-label="Previous image"
                            />
                            <ChevronRightIcon
                                class="h-14 w-14 absolute top-1/2 -translate-y-1/2 right-4 text-gray-500 hover:text-gray-700 transition-colors duration-200 z-10"
                                :class="
                                    localIndex === localAttachments.length - 1
                                        ? 'opacity-50 cursor-not-allowed'
                                        : 'cursor-pointer'
                                "
                                @click="next"
                                tabindex="0"
                                aria-label="Next image"
                            />
                            <XMarkIcon
                                class="h-8 w-8 absolute right-4 top-4 cursor-pointer text-gray-500 hover:text-gray-700 transition-colors duration-200 z-10"
                                @click="closeModal"
                                tabindex="0"
                                aria-label="Close preview"
                            />
                            <img
                                :src="localAttachments[localIndex].path"
                                alt="attachment"
                                class="w-full h-full object-contain"
                            />
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<style scoped>
:deep([tabindex]:focus) {
    outline: none;
}
</style>
