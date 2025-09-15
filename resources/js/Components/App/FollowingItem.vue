<script setup>
defineProps({
    image: String,
    name: String,
})
</script>

<template>
    <div class="flex items-center gap-3 p-3 mb-3 hover:bg-gray-100 rounded cursor-pointer">
        <img :src="image" alt="image" class="w-12 h-12 rounded-full">
        <div>
            <h3 class="font-semibold text-md">{{ name }}</h3>
        </div>
    </div>
</template>

<style scoped>

</style>
