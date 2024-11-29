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
    const addTooltip = document.querySelector(".tooltip-additem");

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

                if (result.status === 'success') {
                    await Swal.fire({
                        title: 'Success!',
                        text: result.message || 'Item added successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: result.message || 'Failed to add the item.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Add request failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        } else {
            Swal.fire({
                title: 'Missing Fields',
                text: 'Please fill out both fields before adding an item.',
                icon: 'warning',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        }
    });

    function updateAddTooltip() {
        const itemName = itemNameInput.value.trim() || "&lt;itemName&gt;";
        const quantity = quantityInput.value.trim() || "&lt;quantity&gt;";
        addTooltip.innerHTML = `
            <span class="sql-keyword">INSERT</span>
            <span class="sql-keyword">INTO</span> inventory (<span class="${itemNameInput.value.trim() ? 'user-value' : 'default-value'}">${itemName}</span>, 
            <span class="${quantityInput.value.trim() ? 'user-value' : 'default-value'}">${quantity}</span>)
        `;
    }

    [itemNameInput, quantityInput].forEach(input =>
        input.addEventListener("input", updateAddTooltip)
    );

    updateAddTooltip();

    const editItemNameInput = document.getElementById("edit-item-name");
    const editQuantityInput = document.getElementById("edit-quantity");
    const updateButton = document.getElementById("update-button");
    const updateTooltip = updateButton.querySelector(".tooltip");

    function updateEditTooltip() {
        const itemName = editItemNameInput.value.trim() || "&lt;itemName&gt;";
        const quantity = editQuantityInput.value.trim() || "&lt;quantity&gt;";
        updateTooltip.innerHTML = `
            <span class="sql-keyword">UPDATE</span> inventory
            <span class="sql-keyword">SET</span>
            item_name=<span class="${editItemNameInput.value.trim() ? 'user-value' : 'default-value'}">${itemName}</span>, 
            quantity=<span class="${editQuantityInput.value.trim() ? 'user-value' : 'default-value'}">${quantity}</span>
            <span class="sql-keyword">WHERE</span> id=<span class="user-value">${document.getElementById("edit-id").value}</span>
        `;
    }

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', () => {
            setTimeout(updateEditTooltip, 0);
        });
    });

    [editItemNameInput, editQuantityInput].forEach(input =>
        input.addEventListener("input", updateEditTooltip)
    );

    updateButton.addEventListener('mouseover', updateEditTooltip);

    updateEditTooltip();
});

document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', async () => {
        const itemId = button.getAttribute('data-id');

        // SweetAlert2 Confirmation Dialog
        const confirmation = await Swal.fire({
            title: 'Are you sure you want to delete?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (confirmation.isConfirmed) {
            try {
                const response = await fetch('delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: itemId }),
                });

                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire({
                        title: 'Deleted!',
                        text: result.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: result.message || 'Failed to delete the item.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            } catch (error) {
                console.error('Delete request failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to delete the item. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }
    });
});

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
        tooltip.style.left = `${tooltipOffset - modalRect.left}px`;
    });
});

const updateButton = document.getElementById('update-button');
const modalContent = document.querySelector('.modal-content');
updateButton.addEventListener('mouseover', () => modalContent.classList.add('expanded-section'));
updateButton.addEventListener('mouseout', () => modalContent.classList.remove('expanded-section'));
