// Handle button hover styles efficiently
const buttonsWithInfo = document.querySelectorAll('button[data-info]');
buttonsWithInfo.forEach(button => {
    button.addEventListener('mouseover', () => {
        button.style.position = 'relative';
    });
    button.addEventListener('mouseout', () => {
        button.style.position = '';
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const itemNameInput = document.getElementById("item-name");
    const quantityInput = document.getElementById("quantity");
    const sqlTooltip = document.querySelector(".tooltip-additem");

    if (!sqlTooltip) {
        console.error("Tooltip element not found!");
        return;
    }

    function updateSQLTooltip() {
        const itemName = itemNameInput.value.trim() || "&lt;itemName&gt;";
        const quantity = quantityInput.value.trim() || "&lt;quantity&gt;";
        sqlTooltip.innerHTML = `
            <span class="sql-keyword">INSERT</span>
            <span class="sql-keyword">INTO</span> inventory (<span class="${itemNameInput.value.trim() ? 'user-value' : 'default-value'}">${itemName}</span>,
            <span class="${quantityInput.value.trim() ? 'user-value' : 'default-value'}">${quantity}</span>)
        `;
    }

    [itemNameInput, quantityInput].forEach(input =>
        input.addEventListener("input", updateSQLTooltip)
    );
});


// Delete Button Functionality
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', async () => {
        const itemId = button.getAttribute('data-id');
        if (confirm('Are you sure you want to delete this item?')) {
            try {
                const response = await fetch('delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: itemId }),
                });
                const result = await response.json();
                alert(result.message);
                if (result.status === 'success') location.reload();
            } catch (error) {
                console.error('Delete request failed:', error);
                alert('Failed to delete the item. Please try again.');
            }
        }
    });
});

// Add Button Functionality
document.getElementById('add-button').addEventListener('click', async () => {
    const itemName = itemNameInput.value.trim();
    const quantity = quantityInput.value.trim();

    if (itemName && quantity) {
        try {
            const response = await fetch('insert.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ item_name: itemName, quantity }),
            });
            const result = await response.json();
            alert(result.message);
            if (result.status === 'success') location.reload();
        } catch (error) {
            console.error('Add request failed:', error);
            alert('Failed to add the item. Please try again.');
        }
    } else {
        alert('Please fill out both fields.');
    }
});

// Edit Modal Functionality
const editModal = document.getElementById('edit-modal');
const closeBtn = document.querySelector('.close-btn');
const editForm = document.getElementById('edit-form');
const editId = document.getElementById('edit-id');
const editItemName = document.getElementById('edit-item-name');
const editQuantity = document.getElementById('edit-quantity');

document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {
        const row = button.closest('tr');
        editId.value = row.querySelector('td:nth-child(1)').textContent.trim();
        editItemName.value = row.querySelector('td:nth-child(2)').textContent.trim();
        editQuantity.value = row.querySelector('td:nth-child(3)').textContent.trim();
        editModal.style.display = 'flex';
    });
});

closeBtn.addEventListener('click', () => {
    editModal.style.display = 'none';
});

editForm.addEventListener('submit', async event => {
    event.preventDefault();
    try {
        const response = await fetch('update.php', {
            method: 'POST',
            body: new FormData(editForm),
        });
        if (response.ok) {
            alert('Item updated successfully!');
            location.reload();
        } else {
            alert('Failed to update item.');
        }
    } catch (error) {
        console.error('Update request failed:', error);
    }
    editModal.style.display = 'none';
});

// Tooltip Positioning & Resizing
buttonsWithInfo.forEach(button => {
    const tooltip = button.querySelector('.tooltip');
    button.addEventListener('mouseover', () => {
        const buttonRect = button.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();
        const modalRect = editModal.getBoundingClientRect();

        const tooltipOffset = Math.max(
            modalRect.left,
            Math.min(buttonRect.left, modalRect.right - tooltipRect.width)
        );
        tooltip.style.left = `${tooltipOffset - modalRect.left}px`; // Fixed template literal syntax
    });
});

// Hover Effect for Update Button
const updateButton = document.getElementById('update-button');
const modalContent = document.querySelector('.modal-content');
updateButton.addEventListener('mouseover', () => modalContent.classList.add('expanded-section'));
updateButton.addEventListener('mouseout', () => modalContent.classList.remove('expanded-section'));
