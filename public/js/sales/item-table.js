// Update total quantity and amount display
function updateTotalQuantity() {
    const unitQuantity = parseFloat(document.getElementById('unit_quantity_input').value) || 0;
    const customQuantity = parseFloat(document.getElementById('custom_quantity_input').value) || 0;
    const rate = parseFloat(document.getElementById('rate_input').value) || 0;
    const taxPercentage = parseFloat(document.getElementById('tax_percentage_input').value) || 0;
    const totalQuantity = unitQuantity * customQuantity;
    const grossAmount = rate * totalQuantity;
    const taxAmount = grossAmount * (taxPercentage / 100);
    const totalAmount = grossAmount + taxAmount;

    document.getElementById('total_quantity_input').value = totalQuantity.toFixed(2);
    document.getElementById('total_amount_display').value = totalAmount.toFixed(2);
}

// Handle Enter key on custom_quantity_input
function handleItemEnter(e) {
    if (e.keyCode === 13) {
        e.preventDefault();
        addItemToTable();
        $('#item_name_input').focus();
    }
}

// ITEM TABLE MANAGEMENT
function addItemToTable() {
    clearErrorMessages();

    const itemId = document.getElementById('item_id_input').value;
    const itemName = document.getElementById('item_name_input').value;
    let rate = parseFloat(document.getElementById('rate_input').value) || 0;
    const unit = document.getElementById('unit_input').value;
    const unitQuantity = parseFloat(document.getElementById('unit_quantity_input').value) || 0;
    const customQuantity = parseFloat(document.getElementById('custom_quantity_input').value) || 0;
    const totalQuantity = parseFloat(document.getElementById('total_quantity_input').value) || 0;
    const taxPercentage = parseFloat(document.getElementById('tax_percentage_input').value) || 0;
    const stock = parseFloat(document.getElementById('stock_input').value) || 0;
    const retailPrice = parseFloat($('#rate_input').data('retail')) || 0;
    const wholesalePrice = parseFloat($('#rate_input').data('wholesale')) || 0;

    // Validate required fields
    if (!itemId || !itemName || rate <= 0 || unitQuantity <= 0 || customQuantity <= 0 || totalQuantity <= 0 || !unit) {
        showErrorMessage('Please fill in all required item fields with valid values.');
        return;
    }

    // Check if item already exists with same rate and unit
    const existingRow = findExistingItemRow(itemId, rate, unit);

    if (existingRow) {
        // Update existing row
        const existingCustomQty = parseFloat($(existingRow).find('input[name^="items["][name$="[custom_quantity]"]').val());
        const existingTotalQty = parseFloat($(existingRow).find('input[name^="items["][name$="[total_quantity]"]').val());

        // Update quantities
        const newCustomQty = existingCustomQty + customQuantity;
        const newTotalQty = existingTotalQty + totalQuantity;

        // Update row values
        $(existingRow).find('input[name^="items["][name$="[custom_quantity]"]').val(newCustomQty.toFixed(2));
        $(existingRow).find('td:nth-child(5)').text(newCustomQty.toFixed(2));
        $(existingRow).find('input[name^="items["][name$="[total_quantity]"]').val(newTotalQty.toFixed(2));
        $(existingRow).find('td:nth-child(9)').text(newTotalQty.toFixed(2));

        // Recalculate amounts
        const grossAmount = rate * newTotalQty;
        const taxAmount = grossAmount * (taxPercentage / 100);
        const totalAmount = grossAmount + taxAmount;

        // Update amount fields
        $(existingRow).find('input[name^="items["][name$="[total_amount]"]').val(totalAmount.toFixed(2));
        $(existingRow).find('input[name^="items["][name$="[gross_amount]"]').val(grossAmount.toFixed(2));
        $(existingRow).find('input[name^="items["][name$="[tax_amount]"]').val(taxAmount.toFixed(2));
        $(existingRow).find('td:nth-child(10)').text(totalAmount.toFixed(2));
    } else {
        // Add new row
        const priceType = rate === retailPrice ? 'Retail' : 'Wholesale';
        const grossAmount = rate * totalQuantity;
        const taxAmount = grossAmount * (taxPercentage / 100);
        const totalAmount = grossAmount + taxAmount;

        // Get the current row count to use as index
        const rowCount = $('#item-rows tr').length;
        const newIndex = rowCount; // This will be the new index (0-based)

        const row = `
            <tr class="item-row">
                <td class="py-2 px-4 border-b">
                    ${rowCount + 1}
                    <input type="hidden" name="items[${newIndex}][item_id]" value="${itemId}">
                </td>
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[${newIndex}][item_name]" value="${itemName}">${itemName}
                </td>
                <td class="py-2 px-4 border-b relative">
                    <div class="rate-display">${rate.toFixed(2)}</div>
                    <div class="price-type-selector hidden absolute bg-white border border-gray-300 shadow-lg z-10 mt-1 rounded" style="width: 200px;">
                        <div class="p-2 hover:bg-blue-50 cursor-pointer" data-price="${retailPrice}">Retail: ${retailPrice.toFixed(2)}</div>
                        <div class="p-2 hover:bg-blue-50 cursor-pointer" data-price="${wholesalePrice}">Wholesale: ${wholesalePrice.toFixed(2)}</div>
                    </div>
                    <input type="hidden" name="items[${newIndex}][rate]" value="${rate.toFixed(2)}">
                    <input type="hidden" name="items[${newIndex}][price_type]" value="${priceType}">
                    <input type="hidden" name="items[${newIndex}][unit_price]" value="${rate.toFixed(2)}">
                </td>
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[${newIndex}][tax_percentage]" value="${taxPercentage.toFixed(2)}">${taxPercentage.toFixed(2)}
                </td>
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[${newIndex}][custom_quantity]" value="${customQuantity.toFixed(2)}">${customQuantity.toFixed(2)}
                </td>
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[${newIndex}][unit]" value="${unit}">${unit}
                </td>
                <td class="py-2 px-4 border-b hidden">
                    <input type="hidden" name="items[${newIndex}][unit_quantity]" value="${unitQuantity.toFixed(2)}">${unitQuantity.toFixed(2)}
                </td>
                <td class="py-2 px-4 border-b hidden">
                    <input type="hidden" name="items[${newIndex}][stock]" value="${stock.toFixed(2)}">${stock.toFixed(2)}
                </td>
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[${newIndex}][total_quantity]" value="${totalQuantity.toFixed(2)}">${totalQuantity.toFixed(2)}
                </td>
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[${newIndex}][total_amount]" value="${totalAmount.toFixed(2)}">${totalAmount.toFixed(2)}
                    <input type="hidden" name="items[${newIndex}][gross_amount]" value="${grossAmount.toFixed(2)}">
                    <input type="hidden" name="items[${newIndex}][tax_amount]" value="${taxAmount.toFixed(2)}">
                </td>
                <td class="py-2 px-4 border-b">
                    <button type="button" onclick="removeItemRow(this)" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Remove</button>
                </td>
            </tr>`;

        document.getElementById('item-rows').insertAdjacentHTML('beforeend', row);

        // Add click handler for rate cells
        const rateDisplay = $('.rate-display').last();
        const priceSelector = rateDisplay.next('.price-type-selector');

        rateDisplay.on('click', function(e) {
            e.stopPropagation();
            $('.price-type-selector').addClass('hidden');
            priceSelector.toggleClass('hidden');
        });

        // Add handler for price selection
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
    }

    // Clear input fields
    clearItemInputs();
    updateTotals();
    updateRowNumbers();
}

function clearItemInputs() {
    document.getElementById('item_id_input').value = '';
    document.getElementById('item_name_input').value = '';
    document.getElementById('rate_input').value = '';
    document.getElementById('unit_quantity_input').value = '';
    document.getElementById('custom_quantity_input').value = '';
    document.getElementById('total_quantity_input').value = '';
    document.getElementById('unit_input').value = '';
    document.getElementById('tax_percentage_input').value = '';
    document.getElementById('stock_input').value = '';
    document.getElementById('total_amount_display').value = '';
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#item-rows .item-row');
    rows.forEach((row, index) => {
        $(row).find('td:first-child').text(index + 1);
        // Update all the input names to maintain sequential indices
        $(row).find('input[name^="items["]').each(function() {
            const name = $(this).attr('name').replace(/items\[\d+\]/g, `items[${index}]`);
            $(this).attr('name', name);
        });
    });
}

function removeItemRow(button) {
    $(button).closest('.item-row').remove();
    updateRowNumbers();
    updateTotals();
}

function findExistingItemRow(itemId, rate, unit) {
    const rows = document.querySelectorAll('#item-rows .item-row');

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const rowItemId = row.querySelector('input[name^="items["][name$="[item_id]"]').value;
        const rowRate = parseFloat(row.querySelector('input[name^="items["][name$="[rate]"]').value);
        const rowUnit = row.querySelector('input[name^="items["][name$="[unit]"]').value;

        if (rowItemId === itemId && rowRate === rate && rowUnit === unit) {
            return row;
        }
    }

    return null;
}
