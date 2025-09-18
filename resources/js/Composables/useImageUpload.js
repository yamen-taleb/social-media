export function useImageUpload(event, imageForm, coverImage, toast) {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        toast.error('Please select a valid image file');
        return;
    }

    imageForm.image = file;
    const reader = new FileReader();

    reader.onload = () => {
        coverImage.value = reader.result;
    };
    reader.onerror = () => {
        toast.error('Error reading image file');
    };

    reader.readAsDataURL(file);
}
