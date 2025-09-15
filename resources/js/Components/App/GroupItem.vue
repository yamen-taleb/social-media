<script setup>
defineProps({
    image: String,
    name: String,
    description: String,
})
</script>

<template>
    <div class="flex items-center gap-3 p-3 mb-3 hover:bg-gray-100 rounded cursor-pointer">
        <img :src="image" alt="image" class="w-16 h-16 rounded-full">
        <div>
            <h3 class="font-semibold text-lg">{{ name }}</h3>
            <p class="text-gray-500 text-sm hover:text-gray-600">{{ description }}</p>
        </div>
    </div>
</template>

<style scoped>

</style>
