const profileMenu = document.querySelector('.profile-menu');
const dropdownContent = document.querySelector('.dropdown');

if (profileMenu && dropdownContent) {
    let hoverTimeout;

    profileMenu.addEventListener('mouseenter', () => {
        clearTimeout(hoverTimeout);
        dropdownContent.style.display = 'block';
    });

    profileMenu.addEventListener('mouseleave', () => {
        hoverTimeout = setTimeout(() => {
            dropdownContent.style.display = 'none';
        }, 50);
    });
} else {
    console.error('Profile menu or dropdown content not found in the DOM.');
}