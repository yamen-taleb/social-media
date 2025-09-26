<script setup>
import { ref, onMounted, defineProps, defineEmits } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: ''
    },
    isActive: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue', 'click']);
const textarea = ref('');

const adjustHeight = () => {
    if (textarea.value) {
        textarea.value.style.height = 'auto';
        textarea.value.style.height = `${Math.min(textarea.value.scrollHeight, 200)}px`;
    }
};

const handleInput = (event) => {
    emit('update:modelValue', event.target.value);
    adjustHeight();
};

const handleClick = () => {
    emit('click');
};

onMounted(() => {
    adjustHeight();
});
</script>

<template>
    <textarea
        :value="modelValue"
        ref="textarea"
        class="w-full p-3 text-gray-700 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 resize-none overflow-y-auto scrollbar-thin min-h-[44px] max-h-screen"
        :class="isActive ? 'bg-white' : 'hover:bg-gray-50 cursor-pointer'"
        rows="1"
        :placeholder="placeholder"
        @input="handleInput"
        @click="handleClick"
    ></textarea>
</template>
