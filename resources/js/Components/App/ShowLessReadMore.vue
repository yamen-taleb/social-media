<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'

const props = defineProps({
  content: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <div>
    <Disclosure v-slot="{ open }">
      <div v-html="content?.substring(0, 200)" v-if="!open" />
      <DisclosurePanel>
        <div v-html="content" />
      </DisclosurePanel>

      <div class="flex justify-end" v-if="content?.length > 200">
        <DisclosureButton class="text-blue-500 hover:text-blue-600 hover:underline">
          <span v-if="!open">Read more</span>
          <span v-else>Show less</span>
        </DisclosureButton>
      </div>
    </Disclosure>
  </div>
</template>

<style scoped></style>
