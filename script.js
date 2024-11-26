document.querySelectorAll('button[data-info]').forEach(button => {
    button.addEventListener('mouseover', () => {
        button.style.position = 'relative';
    });
    button.addEventListener('mouseout', () => {
        button.style.position = '';
    });
});

const itemNameInput = document.getElementById("item-name");
const quantityInput = document.getElementById("quantity");
const sqlTooltip = document.getElementById("add-tooltip");

function updateSQLTooltip() {
    const itemName = itemNameInput.value.trim() || "&lt;itemName&gt;";
    const quantity = quantityInput.value.trim() || "&lt;quantity&gt;";

    sqlTooltip.innerHTML = `
        <span class="sql-keyword">INSERT</span> 
        <span class="sql-keyword">INTO</span> inventory (<span class="${itemNameInput.value.trim() ? 'user-value' : 'default-value'}">${itemName}</span>, 
        <span class="${quantityInput.value.trim() ? 'user-value' : 'default-value'}">${quantity}</span>)
    `;
}

itemNameInput.addEventListener("input", updateSQLTooltip);
quantityInput.addEventListener("input", updateSQLTooltip);

// Delete button functionality
document.querySelectorAll('.delete-btn').forEach((button) => {
    button.addEventListener('click', async () => {
        const itemId = button.getAttribute('data-id');
        console.log(`Attempting to delete item with ID: ${itemId}`);

        if (confirm('Are you sure you want to delete this item?')) {
            try {
                const response = await fetch('delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: itemId }),
                });
                const result = await response.json();
                console.log('Delete response:', result);

                if (result.status === 'success') {
                    alert(result.message);
                    location.reload();
                } else {
                    alert(`Error: ${result.message}`);
                }
            } catch (error) {
                console.error('Delete request failed:', error);
                alert('Failed to delete the item. Please try again.');
            }
        }
    });
});

// Add button functionality
document.getElementById('add-button').addEventListener('click', async () => {
    const itemName = itemNameInput.value.trim();
    const quantity = quantityInput.value.trim();

    if (itemName && quantity) {
        console.log(`Adding item: ${itemName}, Quantity: ${quantity}`);

        try {
            const response = await fetch('insert.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ item_name: itemName, quantity }),
            });
            const result = await response.json();
            console.log('Add response:', result);

            if (result.status === 'success') {
                alert(result.message);
                location.reload();
            } else {
                alert(`Error: ${result.message}`);
            }
        } catch (error) {
            console.error('Add request failed:', error);
            alert('Failed to add the item. Please try again.');
        }
    } else {
        alert('Please fill out both fields.');
    }
});

// Select elements
const editButtons = document.querySelectorAll('.edit-btn');
const editModal = document.getElementById('edit-modal');
const closeBtn = document.querySelector('.close-btn');
const editForm = document.getElementById('edit-form');
const editId = document.getElementById('edit-id');
const editItemName = document.getElementById('edit-item-name');
const editQuantity = document.getElementById('edit-quantity');

// Open modal and populate fields
editButtons.forEach(button => {
    button.addEventListener('click', function () {
        const row = this.closest('tr');
        const id = row.querySelector('td:nth-child(1)').textContent;
        const itemName = row.querySelector('td:nth-child(2)').textContent;
        const quantity = row.querySelector('td:nth-child(3)').textContent;

        editId.value = id.trim();
        editItemName.value = itemName.trim();
        editQuantity.value = quantity.trim();

        editModal.style.display = 'flex'; // Show modal
    });
});

// Close modal
closeBtn.addEventListener('click', () => {
    editModal.style.display = 'none';
});

// Submit form to update database
editForm.addEventListener('submit', async function (event) {
    event.preventDefault();

    const formData = new FormData(editForm);

    const response = await fetch('update_inventory.php', {
        method: 'POST',
        body: formData,
    });

    if (response.ok) {
        alert('Item updated successfully!');
        location.reload(); // Reload to reflect changes
    } else {
        alert('Failed to update item.');
    }

    editModal.style.display = 'none'; // Close modal
});
