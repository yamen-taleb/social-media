<script setup>
import GroupItem from '@/Components/App/GroupItem.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref } from 'vue'

const searchKeyword = ref('')
</script>

<template>
  <div class="scrollbar-thin flex-1 overflow-y-auto py-6">
    <div class="text-center font-semibold text-gray-400" v-if="false">
      You are not joining any groups.
    </div>
    <div v-else>
      <TextInput class="mb-2" :model-value="searchKeyword" placeholder="Type to search" />
      <GroupItem
        image="https://picsum.photos/100"
        name="Laravel Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
      <GroupItem
        image="https://picsum.photos/100"
        name="Vue Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
      <GroupItem
        image="https://picsum.photos/100"
        name="Laravel Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
      <GroupItem
        image="https://picsum.photos/100"
        name="Vue Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
      <GroupItem
        image="https://picsum.photos/100"
        name="Laravel Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
      <GroupItem
        image="https://picsum.photos/100"
        name="Vue Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
      <GroupItem
        image="https://picsum.photos/100"
        name="Laravel Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
      <GroupItem
        image="https://picsum.photos/100"
        name="Vue Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
      <GroupItem
        image="https://picsum.photos/100"
        name="Laravel Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
      <GroupItem
        image="https://picsum.photos/100"
        name="Vue Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
      <GroupItem
        image="https://picsum.photos/100"
        name="Laravel Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
      <GroupItem
        image="https://picsum.photos/100"
        name="Vue Developers"
        description="Lorem ipsum dolor sit amet, consectetur adtipisicing elit. Blanditiis eum "
      />
    </div>
  </div>
</template>

<style scoped></style>
