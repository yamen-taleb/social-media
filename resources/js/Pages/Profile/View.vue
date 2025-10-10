<script setup>
import { computed } from 'vue'
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from '@headlessui/vue'
import { usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Edit from '@/Pages/Profile/Edit.vue'
import TabItem from '@/Components/App/TabItem.vue'
import CoverPicture from '@/Pages/Profile/Partials/CoverPicture.vue'
import ProfilePicture from '@/Pages/Profile/Partials/ProfilePicture.vue'
import { useUpdateImage } from '@/Composables/useUpdateImage.js'

const authUser = usePage().props.auth.user

const isMyProfile = computed(() => authUser && authUser.id === props.user.id)

const props = defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
  user: {
    type: Object,
  },
})

const updateProfilePicture = (imageForm) => {
  useUpdateImage(imageForm, 'profile.avatar')
}

const updateCoverPicture = (imageForm) => {
  useUpdateImage(imageForm, 'profile.cover')
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="mx-auto h-full w-[768px] overflow-auto">
      <div class="bg-white">
        <CoverPicture
          :allowed-to-updated="isMyProfile"
          :cover-path="user.cover"
          @update="updateCoverPicture"
        />
        <div class="flex">
          <ProfilePicture
            :allowed-to-updated="isMyProfile"
            :avatar="user.avatar"
            @update="updateProfilePicture"
          />
          <h2 class="flex-1 p-4 text-lg font-bold">{{ user.name }}</h2>
        </div>
      </div>
      <div class="border-t">
        <TabGroup>
          <TabList class="flex bg-white">
            <Tab v-if="isMyProfile" v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="About" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Posts" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Followers" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Followings" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Photos" />
            </Tab>
          </TabList>

          <TabPanels class="mt-2">
            <TabPanel v-if="isMyProfile">
              <Edit :must-verify-email="mustVerifyEmail" :status="status" />
            </TabPanel>
            <TabPanel class="bg-white p-3 shadow"> Posts</TabPanel>
            <TabPanel class="bg-white p-3 shadow"> Followers</TabPanel>
            <TabPanel class="bg-white p-3 shadow"> Followings</TabPanel>
            <TabPanel class="bg-white p-3 shadow"> Photos</TabPanel>
          </TabPanels>
        </TabGroup>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped></style>
