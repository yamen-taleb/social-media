<script setup>
import { Head } from '@inertiajs/vue3'
import GroupList from '@/Components/App/GroupList.vue'
import FollowingList from '@/Components/App/FollowingList.vue'
import PostList from '@/Components/App/PostList.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  posts: {
    type: Object,
  },
  groups: {
    type: Array,
  },
})
</script>

<template>
  <Head title="Home" />
  <AuthenticatedLayout>
    <div class="grid gap-3 px-1 py-3 lg:grid-cols-12">
      <div class="hidden lg:col-span-3 lg:block">
        <GroupList :groups />
      </div>
      <div class="lg:col-span-6">
        <PostList :posts="posts.data" />
      </div>
      <div class="hidden lg:col-span-3 lg:block">
        <FollowingList />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
