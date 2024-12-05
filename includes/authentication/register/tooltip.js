document.addEventListener('DOMContentLoaded', () => {
    const usernameInput = document.querySelector('input[name="username"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
    const registerButton = document.querySelector('.btn');
    const tooltip = registerButton.querySelector('.tooltip');

    function encryptPassword(password) {
        if (!password) return '<password>';
        return btoa(password);
    }

    function updateTooltip() {
        const username =
            usernameInput.value.trim() !== '' ?
                `<span class="value">${usernameInput.value}</span>` :
                `<span class="default-value">&lt;username&gt;</span>`;

        const password =
            passwordInput.value.trim() !== '' ?
                `<span class="value">${passwordInput.value}</span>` :
                `<span class="default-value">&lt;password&gt;</span>`;

        const encryptedPassword =
            passwordInput.value.trim() !== '' ?
                `<span class="value">${encryptPassword(passwordInput.value)}</span>` :
                `<span class="default-value">&lt;password&gt;</span>`;

        tooltip.innerHTML = `
        <i class="ri-login-box-line"></i> |
        <span class="keyword">INSERT INTO</span> 
        <span class="normal-text">users (username, password, created_at, profile_image)</span>
        <span class="keyword">VALUES</span> 
        <span style="color: #007bff" class="normal-text">(${username}, ${encryptedPassword}, NOW(), 'default.png')</span>
        `;
    }

    usernameInput.addEventListener('input', updateTooltip);
    passwordInput.addEventListener('input', updateTooltip);
    confirmPasswordInput.addEventListener('input', updateTooltip);

    registerButton.addEventListener('mouseover', () => {
        tooltip.style.visibility = 'visible';
        tooltip.style.opacity = '1';
    });

    registerButton.addEventListener('mouseout', () => {
        tooltip.style.visibility = 'hidden';
        tooltip.style.opacity = '0';
    });

    updateTooltip();
});
