function loadBillDetails(billId) {
    $('#bill_suggestions').html('<div class="p-2 text-gray-600">Loading bill details...</div>');
    $('#bill_suggestions').removeClass('hidden');

    $.ajax({
        url: `http://vanapp-sales.test/sales/load-bill/${billId}`,
        method: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Bill Details Response:', response);
            if (!response.master || !response.items) {
                showErrorMessage('Invalid bill data received.');
                $('#bill_suggestions').addClass('hidden');
                return;
            }

            const master = response.master;
            $('#sale_date').val(master.sale_date);
            $('#sale_time').val(master.sale_time);
            $('#customer_id').val(master.customer_id);
            $('#customer_name').val(master.customer_name);
            $('#customer_address').val(master.customer?.address || '');
            $('#payment_option').val(master.sale_type || 'Cash');
            $('#discount').val(master.discount || '');
            $('#narration').val(master.narration || '');
            $('#cash_amount').val(master.cash_amount || '');
            $('#upi_amount').val(master.upi_amount || '');
            $('#card_amount').val(master.card_amount || '');
            $('#credit_amount').val(master.credit_amount || '');

            $('#item-rows').empty();
            if (response.items && response.items.length > 0) {
                response.items.forEach(function(item, index) {
                    addItemFromBill(item, index);
                });
            }

            updateTotals();
            $('form').attr('action', `http://vanapp-sales.test/sales/update-bill/${billId}`);
            $('form').attr('method', 'POST');
            $('form').find('input[name="_method"]').remove();
            $('form').append('<input type="hidden" name="_method" value="PUT">');
            $('form button[type="submit"]').text('Update Sale');

            $('#bill_suggestions').addClass('hidden');
        },
        error: function(xhr, status, error) {
            console.error('Error loading bill details:', error, xhr.status, xhr.responseText);
            showErrorMessage('Error loading bill details. Please try again.');
            $('#bill_suggestions').addClass('hidden');
        }
    });
}

function addItemFromBill(item, index) {
    const rowCount = index + 1;
    const row = `
    <tr class="item-row">
        <td class="py-2 px-4 border-b">
            ${rowCount}
            <input type="hidden" name="items[${index}][item_id]" value="${item.item_id}">
        </td>
        <td class="py-2 px-4 border-b">
            <input type="hidden" name="items[${index}][item_name]" value="${item.item_name}">${item.item_name}
        </td>
        <td class="py-2 px-4 border-b relative">
            <div class="rate-display">${parseFloat(item.rate).toFixed(2)}</div>
            <input type="hidden" name="items[${index}][rate]" value="${parseFloat(item.rate).toFixed(2)}">
            <input type="hidden" name="items[${index}][price_type]" value="${item.price_type || 'Retail'}">
            <input type="hidden" name="items[${index}][unit_price]" value="${parseFloat(item.unit_price || item.rate).toFixed(2)}">
        </td>
        <td class="py-2 px-4 border-b">
            <input type="hidden" name="items[${index}][tax_percentage]" value="${parseFloat(item.tax_percentage || 0).toFixed(2)}">${parseFloat(item.tax_percentage || 0).toFixed(2)}
        </td>
        <td class="py-2 px-4 border-b">
            <input type="hidden" name="items[${index}][custom_quantity]" value="${parseFloat(item.custom_quantity || item.total_quantity / (item.unit_quantity || 1)).toFixed(2)}">${parseFloat(item.custom_quantity || item.total_quantity / (item.unit_quantity || 1)).toFixed(2)}
        </td>
        <td class="py-2 px-4 border-b">
            <input type="hidden" name="items[${index}][unit]" value="${item.unit}">${item.unit}
        </td>
        <td class="py-2 px-4 border-b hidden">
            <input type="hidden" name="items[${index}][unit_quantity]" value="${parseFloat(item.unit_quantity || 1).toFixed(2)}">${parseFloat(item.unit_quantity || 1).toFixed(2)}
        </td>
        <td class="py-2 px-4 border-b hidden">
            <input type="hidden" name="items[${index}][stock]" value="${parseFloat(item.stock || 0).toFixed(2)}">${parseFloat(item.stock || 0).toFixed(2)}
        </td>
        <td class="py-2 px-4 border-b">
            <input type="hidden" name="items[${index}][total_quantity]" value="${parseFloat(item.total_quantity).toFixed(2)}">${parseFloat(item.total_quantity).toFixed(2)}
        </td>
        <td class="py-2 px-4 border-b">
            <input type="hidden" name="items[${index}][total_amount]" value="${parseFloat(item.total_amount).toFixed(2)}">${parseFloat(item.total_amount).toFixed(2)}
            <input type="hidden" name="items[${index}][gross_amount]" value="${parseFloat(item.gross_amount).toFixed(2)}">
            <input type="hidden" name="items[${index}][tax_amount]" value="${parseFloat(item.tax_amount).toFixed(2)}">
        </td>
        <td class="py-2 px-4 border-b">
            <button type="button" onclick="removeItemRow(this)" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Remove</button>
        </td>
    </tr>`;

    $('#item-rows').append(row);
}

function clearForm() {
    $('form').attr('action', 'http://vanapp-sales.test/sales');
    $('form').attr('method', 'POST');
    $('form').find('input[name="_method"]').remove();
    $('form button[type="submit"]').text('Save Sale');

    $('#customer_id').val('');
    $('#customer_name').val('');
    $('#customer_address').val('');
    $('#payment_option').val('Cash');
    $('#discount').val('');
    $('#narration').val('');
    $('#cash_amount').val('');
    $('#upi_amount').val('');
    $('#card_amount').val('');
    $('#credit_amount').val('');
    $('#item-rows').empty();

    updateTotals();
}
