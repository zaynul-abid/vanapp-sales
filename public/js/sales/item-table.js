function updateTotalQuantity() {
    const unitQuantity = parseFloat(document.getElementById('unit_quantity_input').value) || 0;
    const customQuantity = parseFloat(document.getElementById('custom_quantity_input').value) || 0;
    const totalQuantity = unitQuantity * customQuantity;
    document.getElementById('total_quantity_input').value = totalQuantity.toFixed(2);
}

function handleItemEnter(e) {
    if (e.keyCode === 13) {
        e.preventDefault();
        addItemToTable();
        $('#item_name_input').focus();
    }
}

// Helper function to escape HTML special characters
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function addItemToTable() {
    clearErrorMessages();

    // Retrieve input values with fallbacks
    const itemId = document.getElementById('item_id_input').value.trim() || '';
    const itemName = document.getElementById('item_name_input').value.trim() || '';
    const rate = parseFloat(document.getElementById('rate_input').value) || 0;
    const unit = document.getElementById('unit_input').value.trim() || 'Unit';
    const unitQuantity = parseFloat(document.getElementById('unit_quantity_input').value) || 1;
    const customQuantity = parseFloat(document.getElementById('custom_quantity_input').value) || 0;
    const totalQuantity = parseFloat(document.getElementById('total_quantity_input').value) || 0;
    const taxPercentage = parseFloat(document.getElementById('tax_percentage_input').value) || 0;
    const stock = parseFloat(document.getElementById('stock_input').value) || 0;
    const retailPrice = parseFloat($('#rate_input').data('retail')) || 0;
    const wholesalePrice = parseFloat($('#rate_input').data('wholesale')) || 0;

    // Log inputs for debugging
    console.log('Adding item:', {
        itemId,
        itemName,
        rate,
        unit,
        unitQuantity,
        customQuantity,
        totalQuantity,
        taxPercentage,
        stock,
        retailPrice,
        wholesalePrice
    });

    // Validate required fields
    if (!itemId || !itemName || rate <= 0 || unitQuantity <= 0 || customQuantity <= 0 || totalQuantity <= 0) {
        showErrorMessage('Please fill all required item fields with valid values.');
        return;
    }

    // Calculate existing quantity for this item in the table
    let existingQuantity = 0;
    const rows = document.querySelectorAll('.item-row');
    rows.forEach(row => {
        const rowItemId = row.querySelector('input[name^="items["][name$="[item_id]"]').value;
        const rowTotalQuantity = parseFloat(row.querySelector('input[name^="items["][name$="[total_quantity]"]').value) || 0;
        if (rowItemId === itemId) {
            existingQuantity += rowTotalQuantity;
        }
    });

    const totalItemQuantity = existingQuantity + totalQuantity;

    // Validate stock
    if (totalItemQuantity > stock && stock > 0) {
        showErrorMessage(`Total quantity (${totalItemQuantity.toFixed(2)}) for item ${itemName} exceeds available stock (${stock.toFixed(2)}).`);
        return;
    }

    // Calculate amounts
    const priceType = rate === retailPrice ? 'Retail' : (rate === wholesalePrice ? 'Wholesale' : 'Custom');
    const grossAmount = rate * totalQuantity;
    const taxAmount = grossAmount * (taxPercentage / 100);
    const totalAmount = grossAmount + taxAmount;

    // Generate unique index for the row
    const rowCount = $('#item-rows tr').length;

    // Create table row with escaped data
    const row = `
        <tr class="item-row" data-row-index="${rowCount}">
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][item_id]" value="${escapeHtml(itemId)}">${escapeHtml(itemId)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][item_name]" value="${escapeHtml(itemName)}">${escapeHtml(itemName)}
            </td>
            <td class="py-2 px-4 border-b relative">
                <div class="rate-display cursor-pointer">${rate.toFixed(2)}</div>
                <div class="price-type-selector hidden absolute bg-white border border-gray-300 shadow-lg z-10 mt-1 rounded" style="width: 200px;">
                    <div class="p-2 hover:bg-blue-50 cursor-pointer" data-price="${retailPrice}">Retail: ${retailPrice.toFixed(2)}</div>
                    <div class="p-2 hover:bg-blue-50 cursor-pointer" data-price="${wholesalePrice}">Wholesale: ${wholesalePrice.toFixed(2)}</div>
                </div>
                <input type="hidden" name="items[${rowCount}][rate]" value="${rate.toFixed(2)}">
                <input type="hidden" name="items[${rowCount}][price_type]" value="${priceType}">
                <input type="hidden" name="items[${rowCount}][unit_price]" value="${rate.toFixed(2)}">
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][unit]" value="${escapeHtml(unit)}">${escapeHtml(unit)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][unit_quantity]" value="${unitQuantity.toFixed(2)}">${unitQuantity.toFixed(2)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][custom_quantity]" value="${customQuantity.toFixed(2)}">${customQuantity.toFixed(2)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][total_quantity]" value="${totalQuantity.toFixed(2)}">${totalQuantity.toFixed(2)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][tax_percentage]" value="${taxPercentage.toFixed(2)}">${taxPercentage.toFixed(2)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][stock]" value="${stock.toFixed(2)}">${stock.toFixed(2)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][total_amount]" value="${totalAmount.toFixed(2)}">${totalAmount.toFixed(2)}
                <input type="hidden" name="items[${rowCount}][gross_amount]" value="${grossAmount.toFixed(2)}">
                <input type="hidden" name="items[${rowCount}][tax_amount]" value="${taxAmount.toFixed(2)}">
            </td>
            <td class="py-2 px-4 border-b">
                <button type="button" onclick="removeItemRow(this)" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Remove</button>
            </td>
        </tr>`;

    // Log the generated HTML for debugging
    console.log('Generated row HTML:', row);

    // Append row to table
    const itemRows = document.getElementById('item-rows');
    itemRows.insertAdjacentHTML('beforeend', row);

    // Verify row was added
    const addedRow = itemRows.querySelector(`tr[data-row-index="${rowCount}"]`);
    if (!addedRow) {
        console.error('Failed to add row to table');
        showErrorMessage('Error adding item to table. Please try again.');
        return;
    }

    // Set up event listeners for the new row's price selector
    const newRow = $(`tr[data-row-index="${rowCount}"]`);
    const rateDisplay = newRow.find('.rate-display');
    const priceSelector = newRow.find('.price-type-selector');

    rateDisplay.on('click', function(e) {
        e.stopPropagation();
        $('.price-type-selector').addClass('hidden');
        priceSelector.toggleClass('hidden');
    });

    priceSelector.find('div').on('click', function() {
        const newPrice = parseFloat($(this).data('price'));
        const row = $(this).closest('tr');
        row.find('.rate-display').text(newPrice.toFixed(2));
        row.find('input[name^="items["][name$="[rate]"]').val(newPrice.toFixed(2));
        row.find('input[name^="items["][name$="[unit_price]"]').val(newPrice.toFixed(2));
        row.find('input[name^="items["][name$="[price_type]"]').val($(this).text().includes('Retail') ? 'Retail' : 'Wholesale');
        priceSelector.addClass('hidden');
        updateTotals();
    });

    // Clear input fields
    document.getElementById('item_id_input').value = '';
    document.getElementById('item_name_input').value = '';
    document.getElementById('rate_input').value = '';
    document.getElementById('unit_quantity_input').value = '';
    document.getElementById('custom_quantity_input').value = '';
    document.getElementById('total_quantity_input').value = '';
    document.getElementById('unit_input').value = '';
    document.getElementById('tax_percentage_input').value = '';
    document.getElementById('stock_input').value = '';
    $('#rate_input').removeData('retail').removeData('wholesale');

    // Update totals and focus
    updateTotals();
    $('#item_name_input').focus();

    console.log('Item added successfully, row count:', rowCount + 1);
}

function removeItemRow(button) {
    button.closest('.item-row').remove();
    updateTotals();
}

$(document).on('click', function() {
    $('.price-type-selector').addClass('hidden');
});
