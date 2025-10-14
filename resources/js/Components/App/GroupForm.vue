<script setup>
import Checkbox from '@/Components/Checkbox.vue'
import AutoResizeTextarea from '@/Components/App/AutoResizeTextarea.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import TextInput from '@/Components/TextInput.vue'

defineProps({
  form: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <div>
    <InputLabel class="mb-1.5" for="name" value="Group Name" />
    <TextInput
      id="name"
      v-model="form.name"
      autofocus
      class="w-full"
      placeholder="Enter group name"
      required
      type="text"
    />
    <InputError :message="form.errors.name" class="mt-1" />
  </div>
  <div>
    <InputLabel class="mb-1.5" for="description" value="Description" />
    <AutoResizeTextarea
      id="description"
      v-model="form.description"
      class="w-full"
      placeholder="What is this group about?"
    />
    <InputError :message="form.errors.description" class="mt-1" />
  </div>
  <div class="flex items-start space-x-3 rounded-lg bg-gray-50 p-4">
    <Checkbox id="auto_approval" v-model:checked="form.auto_approval" class="mt-0.5" />
    <div class="flex-1">
      <label class="cursor-pointer" for="auto_approval">
        <span class="block font-medium text-gray-900">Auto-approve members</span>
        <span class="text-sm text-gray-500">
          New members will be automatically approved to join
        </span>
      </label>
    </div>
  </div>
</template>
