<template>
  <AuthenticatedLayout>
    <div class="mx-auto h-full w-[768px] overflow-auto">
      <div class="bg-white">
        <CoverPicture :cover-path="user.cover" />
        <div class="flex">
          <ProfilePicture :avatar="user.avatar" />
          <div class="flex flex-1 items-center justify-between p-4">
            <h2 class="text-lg font-bold">{{ user.name }}</h2>
            <PrimaryButton v-if="isMyProfile">
              <Pen />
              Edit Profile
            </PrimaryButton>
          </div>
        </div>
      </div>
      <div class="border-t">
        <TabGroup>
          <TabList class="flex bg-white">
            <Tab v-if="isMyProfile" v-slot="{ selected }" as="template">
              <TabItem text="About" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Posts" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Followers" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Followings" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Photos" :selected="selected" />
            </Tab>
          </TabList>

          <TabPanels class="mt-2">
            <TabPanel v-if="isMyProfile">
              <Edit :must-verify-email="mustVerifyEmail" :status="status" />
            </TabPanel>
            <TabPanel class="bg-white p-3 shadow"> Posts </TabPanel>
            <TabPanel class="bg-white p-3 shadow"> Followers </TabPanel>
            <TabPanel class="bg-white p-3 shadow"> Followings </TabPanel>
            <TabPanel class="bg-white p-3 shadow"> Photos </TabPanel>
          </TabPanels>
        </TabGroup>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Edit from '@/Pages/Profile/Edit.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TabItem from '@/Components/App/TabItem.vue'
import Pen from '@/Components/Icons/Pen.vue'
import Photo from '@/Components/Icons/Photo.vue'
import CoverPicture from '@/Pages/Profile/Partials/CoverPicture.vue'
import ProfilePicture from '@/Pages/Profile/Partials/ProfilePicture.vue'

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
</script>

<style scoped></style>
