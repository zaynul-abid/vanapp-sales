function validateForm() {
    clearErrorMessages();

    const netTotalAmount = parseFloat(document.getElementById('net_total_amount').value) || 0;
    const totalPayment = parseFloat(document.getElementById('total_payment_amount').value) || 0;
    const rows = document.querySelectorAll('.item-row');
    let isValid = true;

    const itemQuantities = {};
    const itemDetails = {};

    rows.forEach(row => {
        const itemId = row.querySelector('input[name^="items["][name$="[item_id]"]').value;
        const itemName = row.querySelector('input[name^="items["][name$="[item_name]"]').value;
        const totalQuantity = parseFloat(row.querySelector('input[name^="items["][name$="[total_quantity]"]').value) || 0;
        const stock = parseFloat(row.querySelector('input[name^="items["][name$="[stock]"]').value) || 0;

        if (!itemQuantities[itemId]) {
            itemQuantities[itemId] = 0;
            itemDetails[itemId] = { name: itemName, stock: stock };
        }
        itemQuantities[itemId] += totalQuantity;
    });

    for (const itemId in itemQuantities) {
        const totalQuantity = itemQuantities[itemId];
        const { name, stock } = itemDetails[itemId];
        if (totalQuantity > stock) {
            showErrorMessage(`Total quantity (${totalQuantity.toFixed(2)}) for item ${name} exceeds available stock (${stock.toFixed(2)}).`);
            isValid = false;
        }
    }

    if (Math.abs(netTotalAmount - totalPayment) > 0.01) {
        showErrorMessage('Total payment amount must equal the net total amount.');
        isValid = false;
    }

    return isValid;
}
