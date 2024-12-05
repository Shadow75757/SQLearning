document.addEventListener('DOMContentLoaded', () => {
    const usernameInput = document.querySelector('input[name="username"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const loginButton = document.getElementById('login-button');
    const tooltip = loginButton.querySelector('.tooltip');

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
<span class="keyword">SELECT</span> 
<span class="normal-text">id, password</span>
<span class="keyword">FROM</span> 
<span class="normal-text">users</span>
<span class="keyword">WHERE</span>
<span class="normal-text">username =</span> ${username}
<span class="keyword">AND</span>
<span class="normal-text">password =</span> ${encryptedPassword}
`;
    }

    usernameInput.addEventListener('input', updateTooltip);
    passwordInput.addEventListener('input', updateTooltip);

    loginButton.addEventListener('mouseover', () => {
        tooltip.style.visibility = 'visible';
        tooltip.style.opacity = '1';
    });

    loginButton.addEventListener('mouseout', () => {
        tooltip.style.visibility = 'hidden';
        tooltip.style.opacity = '0';
    });

    updateTooltip();
});