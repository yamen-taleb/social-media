<script setup>
import FollowingItem from '@/Components/App/FollowingItem.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref } from 'vue'

const searchKeyword = ref('')
</script>

<template>
  <div class="scrollbar-thin overflow-y-auto py-6">
    <div class="text-center font-semibold text-gray-400" v-if="false">
      You don't have friends yet!
    </div>
    <div v-else>
      <TextInput class="mb-2" :model-value="searchKeyword" placeholder="Type to search" />
      <FollowingItem image="https://picsum.photos/100" name="Laravel Developers" />
      <FollowingItem image="https://picsum.photos/100" name="Vue Developers" />
      <FollowingItem image="https://picsum.photos/100" name="Laravel Developers" />
      <FollowingItem image="https://picsum.photos/100" name="Vue Developers" />
    </div>
  </div>
</template>

<style scoped></style>
