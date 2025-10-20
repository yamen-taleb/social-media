import { useToast } from 'vue-toastification'

async function useCopy(text) {
  try {
    if (navigator.clipboard && window.isSecureContext) {
      await navigator.clipboard.writeText(text)
      useToast().success('copied to clipboard!')
    } else {
      const textArea = document.createElement('textarea')
      textArea.value = text
      textArea.style.position = 'fixed'
      document.body.appendChild(textArea)
      textArea.focus()
      textArea.select()
      document.execCommand('copy')
      document.body.removeChild(textArea)
      useToast().success('copied to clipboard!')
    }
  } catch (error) {
    useToast().error('Failed to copy. Please try again.')
  }
}

export default useCopy
