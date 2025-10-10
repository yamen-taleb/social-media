import { useToast } from 'vue-toastification'

export function useUpdateImage(imageForm, routeName, param = null) {
  if (!imageForm.image) {
    useToast().error('No image selected')
    return
  }

  imageForm.post(route(routeName, param), {
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
