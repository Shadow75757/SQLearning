function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        const preview = document.getElementById('profileImagePreview');
        preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}