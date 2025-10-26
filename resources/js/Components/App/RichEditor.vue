<script setup>
import {
  BlockQuote,
  Bold,
  ClassicEditor,
  Essentials,
  Heading,
  Italic,
  Link,
  List,
  Paragraph,
  Undo,
} from 'ckeditor5'
import { Ckeditor } from '@ckeditor/ckeditor5-vue'
import { onBeforeUnmount, ref, watch } from 'vue'
import 'ckeditor5/ckeditor5.css'
import '../../../css/ckeditor.css'

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['update:modelValue'])

const editorInstance = ref(null)
const editorData = ref(props.modelValue || '')
const isUpdating = ref(false)

// Handle editor initialization
const onReady = (editor) => {
  editorInstance.value = editor
  editor.setData(props.modelValue || '')

  // Listen for changes
  const modelDocument = editor.model.document
  modelDocument.on('change:data', () => {
    if (isUpdating.value) return

    const data = editor.getData()
    emit('update:modelValue', data)
  })
}

// Watch for changes from parent
watch(
  () => props.modelValue,
  (newValue) => {
    if (!editorInstance.value || newValue === editorInstance.value.getData()) {
      return
    }

    isUpdating.value = true
    try {
      editorInstance.value.setData(newValue)
    } finally {
      // Use nextTick to ensure the update is complete
      setTimeout(() => {
        isUpdating.value = false
      }, 0)
    }
  }
)

// Cleanup
onBeforeUnmount(() => {
  if (editorInstance.value) {
    editorInstance.value.destroy()
    editorInstance.value = null
  }
})

const config = {
  licenseKey: 'GPL',
  plugins: [Essentials, Paragraph, List, Bold, Italic, Undo, Link, BlockQuote, Heading],
  toolbar: [
    'heading',
    '|',
    'bold',
    'italic',
    'link',
    '|',
    'bulletedList',
    'numberedList',
    'blockQuote',
    '|',
    'undo',
    'redo',
  ],
  typing: {
    undoStep: 1,
  },
  undo: {
    undoStep: 1,
  },
  heading: {
    options: [
      {
        model: 'paragraph',
        title: 'Paragraph',
        class: 'ck-heading_paragraph',
      },
      {
        model: 'heading2',
        view: 'h2',
        title: 'Heading 2',
        class: 'ck-heading_heading2',
      },
      {
        model: 'heading3',
        view: 'h3',
        title: 'Heading 3',
        class: 'ck-heading_heading3',
      },
      {
        model: 'heading4',
        view: 'h4',
        title: 'Heading 4',
        class: 'ck-heading_heading4',
      },
    ],
  },
}
</script>

<template>
  <div class="ck-content rich-editor-wrapper dark:rich-editor-dark">
    <ckeditor
      :editor="ClassicEditor"
      :config="config"
      :model-value="editorData || ''"
      @ready="onReady"
      @input="(value) => emit('update:modelValue', value)"
    />
  </div>
</template>

<style scoped>
.rich-editor-wrapper {
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
  overflow: hidden;
  transition: border-color 0.2s ease-in-out;
}

.rich-editor-wrapper:hover {
  border-color: #cbd5e0;
}

.rich-editor-wrapper:focus-within {
  border-color: #4299e1;
  box-shadow: 0 0 0 1px #4299e1;
}

:deep(.ck.ck-editor) {
  max-width: 100%;
}

:deep(.ck.ck-editor__main) {
  min-height: 150px;
}

:deep(.ck.ck-content) {
  min-height: 150px;
  padding: 0.5rem;
  color: #2d3748;
  line-height: 1.625;
}

:deep(.ck.ck-toolbar) {
  border: none;
  border-bottom: 1px solid #e2e8f0;
  background: #f7fafc;
  padding: 0.1rem;
}

:deep(.ck.ck-toolbar__items) {
  gap: 0.25rem;
}

:deep(.ck.ck-button) {
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  transition: background 0.2s ease;
}

:deep(.ck.ck-button:hover),
:deep(.ck.ck-button.ck-on) {
  background: #e2e8f0;
}

:deep(.ck.ck-button.ck-on) {
  color: #2b6cb0;
}

:deep(.ck.ck-dropdown__panel) {
  border-radius: 0.375rem;
  box-shadow:
    0 1px 3px 0 rgba(0, 0, 0, 0.1),
    0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

:deep(blockquote) {
  border-left: 4px solid #4299e1;
  margin: 1rem 0;
  padding-left: 1rem;
  color: #4a5568;
  font-style: italic;
}

:deep(ol) {
  list-style-type: decimal;
  padding-left: 2rem;
  margin: 0.5rem 0;
}

:deep(ul) {
  list-style-type: disc;
  padding-left: 2rem;
  margin: 0.5rem 0;
}

:deep(h2) {
  font-size: 1.5rem;
  font-weight: 600;
  margin: 1.5rem 0 1rem;
  color: #2d3748;
}

:deep(h3) {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 1.25rem 0 0.75rem;
  color: #2d3748;
}

:deep(h4) {
  font-size: 1.125rem;
  font-weight: 600;
  margin: 1rem 0 0.5rem;
  color: #2d3748;
}
</style>
