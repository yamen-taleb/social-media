export function useUpdateImage(imageForm, toast, routeName) {
    if (!imageForm.image) {
        toast.error('No image selected');
        return;
    }

    imageForm.post(route(routeName), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Cover updated successfully');
        },
        onError: (errors) => {
            const errorMessage = errors.image || 'Failed to update cover';
            toast.error(errorMessage);
        }
    });
}
