<script setup>
import { ref } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { Link, usePage } from '@inertiajs/vue3'
import GlobalSearch from '@/Components/App/GlobalSearch.vue'
import ThemeToggle from '@/Components/ThemeToggle.vue'

const showingNavigationDropdown = ref(false)

const authUser = usePage().props.auth.user
</script>

<template>
  <div class="flex h-full flex-col overflow-hidden bg-gray-50 dark:bg-gray-900">
    <nav class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
      <!-- Primary Navigation Menu -->
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
          <div class="flex">
            <!-- Logo -->
            <div class="relative flex shrink-0 items-center gap-8">
              <Link :href="route('home')">
                <ApplicationLogo
                  class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                />
              </Link>
              <GlobalSearch />
            </div>
          </div>

          <div class="hidden gap-4 sm:ms-6 sm:flex sm:items-center">
            <ThemeToggle />
            <!-- Settings Dropdown -->
            <div class="relative ms-3">
              <Dropdown v-if="authUser" align="right" width="48">
                <template #trigger>
                  <span class="inline-flex rounded-md">
                    <button
                      type="button"
                      class="inline-flex items-center rounded-md border border-transparent px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:text-gray-200 dark:hover:text-gray-100"
                    >
                      {{ authUser.name }}

                      <svg
                        class="-me-0.5 ms-2 h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd"
                        />
                      </svg>
                    </button>
                  </span>
                </template>

                <template #content>
                  <DropdownLink :href="route('profile', { username: authUser.username })">
                    Profile
                  </DropdownLink>
                  <DropdownLink :href="route('logout')" method="post" as="button">
                    Log Out
                  </DropdownLink>
                </template>
              </Dropdown>
              <div v-else class="flex items-center gap-2 text-gray-600">
                <Link
                  class="rounded p-2 hover:bg-gray-100 hover:text-gray-700"
                  :href="route('login')"
                >
                  Login
                </Link>
                <div class="h-8 border-r-2"></div>
                <Link
                  class="rounded p-2 hover:bg-gray-100 hover:text-gray-700"
                  :href="route('register')"
                >
                  Register
                </Link>
              </div>
            </div>
          </div>

          <!-- Hamburger -->
          <div class="-me-2 flex items-center sm:hidden">
            <button
              @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="text-gray-400hover:text-gray-500 inline-flex items-center justify-center rounded-md p-2 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
            >
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path
                  :class="{
                    hidden: showingNavigationDropdown,
                    'inline-flex': !showingNavigationDropdown,
                  }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  :class="{
                    hidden: !showingNavigationDropdown,
                    'inline-flex': showingNavigationDropdown,
                  }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Responsive Navigation Menu -->
      <div
        :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
        class="sm:hidden"
      >
        <!-- Responsive Settings Options -->
        <div class="border-t border-gray-200 pb-1 pt-4">
          <template v-if="authUser">
            <div class="px-4">
              <div class="text-base font-medium text-gray-800">
                {{ authUser.name }}
              </div>
              <div class="text-sm font-medium text-gray-500">{{ authUser.email }}</div>
            </div>

            <div class="mt-3 space-y-1">
              <ResponsiveNavLink :href="route('profile', { username: authUser.username })">
                Profile
              </ResponsiveNavLink>
              <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                Log Out
              </ResponsiveNavLink>
            </div>
          </template>
          <template> Login button </template>
        </div>
      </div>
    </nav>

    <!-- Page Heading -->
    <header
      class="bg-white shadow transition-colors dark:bg-gray-800 dark:shadow-gray-700"
      v-if="$slots.header"
    >
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>

    <!-- Page Content -->
    <main
      class="duration-50 flex-1 overflow-y-auto transition-colors duration-100 dark:bg-gray-900"
    >
      <slot />
    </main>
  </div>
</template>
