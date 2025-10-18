<script setup>
import { computed, ref } from 'vue'
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from '@headlessui/vue'
import { InfiniteScroll, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Edit from '@/Pages/Profile/Edit.vue'
import TabItem from '@/Components/App/TabItem.vue'
import CoverPicture from '@/Pages/Profile/Partials/CoverPicture.vue'
import ProfilePicture from '@/Pages/Profile/Partials/ProfilePicture.vue'
import { useUpdateImage } from '@/Composables/useUpdateImage.js'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { useToast } from 'vue-toastification'
import axiosClient from '@/axiosClient.js'
import UserItem from '@/Components/App/UserItem.vue'
import PostList from '@/Components/App/PostList.vue'

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
  followersCount: Number,
  isCurrentUserFollower: Boolean,
  followers: Object,
  following: Object,
  posts: Object,
})

const localIsCurrentUserFollower = ref(props.isCurrentUserFollower)
const localFollowersCount = ref(props.followersCount)
const updateProfilePicture = (imageForm) => {
  useUpdateImage(imageForm, 'profile.avatar')
}

const updateCoverPicture = (imageForm) => {
  useUpdateImage(imageForm, 'profile.cover')
}

const followUser = () => {
  axiosClient
    .post(route('followers.store'), {
      user_id: props.user.id,
    })
    .then(({ data }) => {
      localIsCurrentUserFollower.value = data.isCurrentUserFollower
      if (data.isCurrentUserFollower) {
        localFollowersCount.value += 1
      } else {
        localFollowersCount.value -= 1
      }
      useToast().success(
        data.isCurrentUserFollower ? 'Followed successfully' : 'Unfollowed successfully'
      )
    })
    .catch((e) => {
      console.log(e)
      useToast().error('Operation failed')
    })
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
          <div class="flex flex-1 items-center justify-between pl-4 pr-8">
            <div>
              <h2 class="text-lg font-bold">{{ user.name }}</h2>
              <small class="text-xs text-gray-600">
                {{ localFollowersCount }}
                {{ localFollowersCount === 1 ? 'follower' : 'followers' }}
              </small>
            </div>
            <template v-if="authUser.id !== user.id">
              <PrimaryButton v-if="!localIsCurrentUserFollower" @click="followUser"
                >Follow
              </PrimaryButton>
              <PrimaryButton v-else @click="followUser">Unfollow</PrimaryButton>
            </template>
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
              <TabItem :selected="selected" text="Followers" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Following" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Photos" />
            </Tab>
            <Tab v-if="isMyProfile" v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="About" />
            </Tab>
          </TabList>

          <TabPanels class="mt-2">
            <TabPanel>
              <PostList
                v-if="posts.data.length > 0"
                :posts="posts.data"
                :showCreatePost="authUser.id === user.id"
              />
              <div v-else class="bg-white p-3 text-center text-gray-500 shadow">No Posts Yet</div>
            </TabPanel>
            <TabPanel class="bg-white p-3 shadow">
              <div v-if="followers.data.length > 0" class="space-y-3">
                <InfiniteScroll data="followers">
                  <div class="grid grid-cols-2 gap-2">
                    <UserItem
                      v-for="follower in followers.data"
                      :key="follower.id"
                      :avatar="follower.avatar"
                      :name="follower.name"
                      :username="follower.username"
                    />
                  </div>
                </InfiniteScroll>
              </div>
              <div v-else class="text-center text-gray-500">No Followers</div>
            </TabPanel>
            <TabPanel class="bg-white p-3 shadow">
              <div v-if="following.data.length > 0" class="space-y-3">
                <InfiniteScroll data="following">
                  <div class="grid grid-cols-2 gap-2">
                    <UserItem
                      v-for="follow in following.data"
                      :key="follow.id"
                      :avatar="follow.avatar"
                      :name="follow.name"
                      :username="follow.username"
                    />
                  </div>
                </InfiniteScroll>
              </div>
              <div v-else class="text-center text-gray-500">No Followers</div>
            </TabPanel>
            <TabPanel class="bg-white p-3 shadow"> Photos</TabPanel>
            <TabPanel v-if="isMyProfile">
              <Edit :must-verify-email="mustVerifyEmail" :status="status" />
            </TabPanel>
          </TabPanels>
        </TabGroup>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped></style>
