<script setup>
import { computed, provide, ref } from 'vue'
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from '@headlessui/vue'
import { InfiniteScroll, useForm, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TabItem from '@/Components/App/TabItem.vue'
import CoverPicture from '@/Pages/Profile/Partials/CoverPicture.vue'
import ProfilePicture from '@/Pages/Profile/Partials/ProfilePicture.vue'
import { useUpdateImage } from '@/Composables/useUpdateImage.js'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import GroupAddMembers from '@/Components/App/GroupAddMembers.vue'
import { useToast } from 'vue-toastification'
import UserItem from '@/Components/App/UserItem.vue'
import GroupForm from '@/Components/App/GroupForm.vue'

const authUser = usePage().props.auth.user
const showAddMembersModal = ref(false)
const updateShowAddMembersModal = (value) => {
  showAddMembersModal.value = value
}

provide('show', showAddMembersModal)
provide('updateModel', updateShowAddMembersModal)

const props = defineProps({
  group: {
    type: Object,
    required: true,
  },
  requests: {
    type: [Object, null],
    required: true,
  },
  members: {
    type: Object,
    required: true,
  },
})

const form = useForm({})
const aboutForm = useForm({
  name: props.group.name,
  auto_approval: props.group.auto_approval,
  description: props.group.description,
})
const isAdmin = computed(() => authUser && props.group.role === 'admin')

const updateProfilePicture = (imageForm) => {
  useUpdateImage(imageForm, 'groups.avatar', props.group.slug)
}
const updateCoverPicture = (imageForm) => {
  useUpdateImage(imageForm, 'groups.cover', props.group.slug)
}

const joinToGroup = (action = 'join') => {
  form.post(
    route('groups.members.join', {
      group: props.group.slug,
      user: authUser.id,
    }),
    {
      preserveScroll: true,
      onSuccess: () => {
        useToast().success(
          action === 'join' ? 'You joined successfully' : 'Your request has been sent'
        )
      },
    }
  )
}

const handleRequest = (userId, action) => {
  form.post(
    route('groups.members.handle-request', {
      group: props.group.slug,
      user: userId,
      action: action,
    }),
    {
      preserveScroll: true,
      onSuccess: () => {
        useToast().success(`Request ${action}ed successfully`)
      },
      onError: () => {
        useToast().error(`Failed to ${action} request`)
      },
    }
  )
}

const updateRole = (role, user_id) => {
  form.patch(
    route('groups.members.update-role', {
      group: props.group.slug,
      user: user_id,
      role: role,
    }),
    {
      preserveScroll: true,
      onSuccess: () => {
        useToast().success('Role updated successfully')
      },
      onError: (e) => {
        console.error(e)
        useToast().error('Failed to update role')
      },
    }
  )
}

function updateGroup() {
  aboutForm.put(route('groups.update', props.group.slug), {
    preserveScroll: true,
    onSuccess: () => {
      useToast().success('Group updated successfully')
    },
    onError: () => {
      useToast().error('Failed to update group')
    },
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="mx-auto h-full w-[768px]">
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
            <PrimaryButton v-if="isAdmin" @click="showAddMembersModal = true"
              >Add Members
            </PrimaryButton>
            <PrimaryButton v-if="!group.role && group.auto_approval" @click="joinToGroup">
              Join to Group
            </PrimaryButton>
            <PrimaryButton
              v-if="!group.role && !group.auto_approval"
              @click="joinToGroup('requestToJoin')"
            >
              Request to join
            </PrimaryButton>
          </div>
        </div>
      </div>
      <div class="border-t">
        <TabGroup>
          <TabList class="flex bg-white">
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Posts" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Members" />
            </Tab>
            <Tab v-if="isAdmin" v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Requests" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Photos" />
            </Tab>
            <Tab v-if="isAdmin" v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="About" />
            </Tab>
          </TabList>

          <TabPanels class="mt-2">
            <TabPanel class="bg-white p-3 shadow"> Posts</TabPanel>
            <TabPanel>
              <div v-if="members.data.length > 0" class="space-y-3">
                <InfiniteScroll data="members">
                  <div class="grid grid-cols-2 gap-2">
                    <UserItem
                      v-for="member in members.data"
                      :id="member.id"
                      :key="member.id"
                      :avatar="member.avatar"
                      :currentRole="member.role"
                      :isAdmin
                      :isOwner="member.id === group.owner_id"
                      :name="member.name"
                      :showRoleSelect="true"
                      :username="member.username"
                      @update:role="updateRole"
                    />
                  </div>
                </InfiniteScroll>
              </div>
              <div v-else class="text-center text-gray-500">No Members</div>
            </TabPanel>
            <TabPanel v-if="isAdmin" class="bg-white p-3 shadow">
              <div v-if="requests.data.length > 0" class="space-y-3">
                <InfiniteScroll data="requests">
                  <div class="grid grid-cols-2 gap-2">
                    <UserItem
                      v-for="request in requests.data"
                      :key="request.id"
                      :avatar="request.avatar"
                      :name="request.name"
                      :show-buttons="true"
                      :username="request.username"
                      @handle-request="(action) => handleRequest(request.id, action)"
                    />
                  </div>
                </InfiniteScroll>
              </div>
              <div v-else class="text-center text-gray-500">No pending requests</div>
            </TabPanel>
            <TabPanel class="bg-white p-3 shadow"> Photos</TabPanel>
            <TabPanel class="space-y-4 bg-white p-3 shadow">
              <GroupForm :form="aboutForm" />
              <PrimaryButton @click="updateGroup">Submit</PrimaryButton>
            </TabPanel>
          </TabPanels>
        </TabGroup>
      </div>
    </div>
  </AuthenticatedLayout>
  <GroupAddMembers :group />
</template>

<style scoped></style>
