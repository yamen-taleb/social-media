export function isImage(attachment) {
  return (
    attachment.type?.startsWith('image/') ||
    attachment.file?.type.startsWith('image/') ||
    attachment.type === 'png' ||
    attachment.type === 'jpg'
  )
}

export function isVideo(attachment) {
  return (
    attachment.type?.startsWith('video/') ||
    attachment.file?.type.startsWith('video/') ||
    attachment.type === 'mp4'
  )
}
