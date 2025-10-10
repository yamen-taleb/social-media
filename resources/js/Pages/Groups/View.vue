<script setup>
import { computed } from 'vue'
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from '@headlessui/vue'
import { usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TabItem from '@/Components/App/TabItem.vue'
import CoverPicture from '@/Pages/Profile/Partials/CoverPicture.vue'
import ProfilePicture from '@/Pages/Profile/Partials/ProfilePicture.vue'
import { useUpdateImage } from '@/Composables/useUpdateImage.js'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const authUser = usePage().props.auth.user

const isAdmin = computed(() => authUser && props.group.role === 'admin')

const props = defineProps({
  group: {
    type: Object,
    required: true,
  },
})

const updateProfilePicture = (imageForm) => {
  useUpdateImage(imageForm, 'groups.avatar', props.group.slug)
}

const updateCoverPicture = (imageForm) => {
  useUpdateImage(imageForm, 'groups.cover', props.group.slug)
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="mx-auto h-full w-[768px] overflow-auto">
      <div class="bg-white">
        <CoverPicture
          :allowed-to-updated="isAdmin"
          :cover-path="group.cover"
          @update="updateCoverPicture"
        />
        <div class="flex">
          <ProfilePicture
            :allowed-to-updated="isAdmin"
            :avatar="group.thumbnail"
            @update="updateProfilePicture"
          />
          <div class="flex flex-1 items-center justify-between p-4">
            <h2 class="text-lg font-bold">{{ group.name }}</h2>
            <PrimaryButton v-if="isAdmin">Add Members</PrimaryButton>
            <PrimaryButton v-if="!group.role && group.auto_approval">Join to Group</PrimaryButton>
            <PrimaryButton v-if="!group.role && !group.auto_approval">
              Request to join
            </PrimaryButton>
          </div>
        </div>
      </div>
      <div class="border-t">
        <TabGroup>
          <TabList class="flex bg-white">
            <Tab v-if="isAdmin" v-slot="{ selected }" as="template">
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
