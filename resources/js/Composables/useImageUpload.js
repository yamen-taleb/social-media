import { useToast } from 'vue-toastification'

export function useImageUpload(event, imageForm, coverImage) {
  const file = event.target.files[0]
  if (!file) return

  if (!file.type.startsWith('image/')) {
    useToast().error('Please select a valid image file')
    return
  }

  imageForm.image = file
  const reader = new FileReader()

  reader.onload = () => {
    coverImage.value = reader.result
  }
  reader.onerror = () => {
    useToast().error('Error reading image file')
  }

  reader.readAsDataURL(file)
}
