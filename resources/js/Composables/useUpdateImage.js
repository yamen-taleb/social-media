import { useToast } from 'vue-toastification'

export function useUpdateImage(imageForm, routeName) {
  if (!imageForm.image) {
    useToast().error('No image selected')
    return
  }

  imageForm.post(route(routeName), {
    preserveScroll: true,
    onSuccess: () => {
      useToast().success('Cover updated successfully')
    },
    onError: (errors) => {
      const errorMessage = errors.image || 'Failed to update cover'
      useToast().error(errorMessage)
    },
  })
}
