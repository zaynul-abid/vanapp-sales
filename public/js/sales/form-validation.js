function validateForm() {
    // Reset error messages
    $('#client-error-messages').empty();

    // Basic form validation (e.g., customer name, items)
    if (!$('#customer_name').val().trim()) {
        showErrorMessage('Please enter a customer name.');
        return false;
    }

    // Check for items
    const itemRows = $('#item-rows .item-row');
    if (itemRows.length === 0) {
        showErrorMessage('Please add at least one item to the order.');
        return false;
    }

    // Check for negative stock
    let hasNegativeStock = false;
    let negativeStockMessage = 'The following items have insufficient stock. Please adjust quantities or confirm to proceed with negative stock:<ul>';
    itemRows.each(function() {
        const stock = parseFloat($(this).find('input[name$="[stock]"]').val()) || 0;
        const totalQuantity = parseFloat($(this).find('input[name$="[total_quantity]"]').val()) || 0;
        const itemName = $(this).find('input[name$="[item_name]"]').val();
        const remainingStock = stock - totalQuantity;

        if (remainingStock < 0) {
            hasNegativeStock = true;
            negativeStockMessage += `<li><strong>${itemName}</strong>: ${remainingStock.toFixed(2)} (Current stock: ${stock.toFixed(2)}, Requested: ${totalQuantity.toFixed(2)})</li>`;
        }
    });
    negativeStockMessage += '</ul>';

    if (hasNegativeStock) {
        // Show negative stock modal
        $('#negativeStockMessage').html(negativeStockMessage);
        $('#negativeStockModal').removeClass('hidden');

        // Store the form for later submission
        window.pendingForm = $('form')[0];

        // Create a promise to handle modal response
        window.negativeStockPromise = new Promise((resolve, reject) => {
            window.negativeStockPromiseResolve = resolve;
            window.negativeStockPromiseReject = reject;
        });

        // Handle the promise resolution
        window.negativeStockPromise.then(() => {
            console.log('User confirmed negative stock, submitting form');
            window.pendingForm.submit();
        }).catch(() => {
            console.log('User canceled negative stock confirmation');
            window.pendingForm = null;
        });

        // Prevent immediate form submission
        return false;
    }

    // If no negative stock, allow form submission
    return true;
}

function showErrorMessage(message) {
    $('#client-error-messages').append(
        `<div class="flex items-center justify-between p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert" aria-live="assertive">
            <div><strong class="font-bold">Error: </strong> ${message}</div>
            <button type="button" class="text-red-700 hover:text-red-900" onclick="this.parentElement.remove()" aria-label="Close error message">✖</button>
        </div>`
    );
}
