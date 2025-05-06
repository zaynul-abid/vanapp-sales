function updateTotals() {
    const rows = document.querySelectorAll('.item-row');
    let grossAmount = 0;
    let taxAmount = 0;

    // Calculate Gross Amount and Tax Amount before discount
    rows.forEach(row => {
        const rate = parseFloat(row.querySelector('input[name^="items["][name$="[rate]"]').value) || 0;
        const totalQuantity = parseFloat(row.querySelector('input[name^="items["][name$="[total_quantity]"]').value) || 0;
        const taxPercentage = parseFloat(row.querySelector('input[name^="items["][name$="[tax_percentage]"]').value) || 0;

        const itemGross = rate * totalQuantity;
        const itemTax = itemGross * (taxPercentage / 100);
        grossAmount += itemGross;
        taxAmount += itemTax;
    });

    const totalAmount = grossAmount + taxAmount;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const netGrossAmount = grossAmount - discount;

    // Recalculate tax after discount by distributing discount proportionally
    let netTaxAmount = 0;
    if (grossAmount > 0) {
        rows.forEach(row => {
            const rate = parseFloat(row.querySelector('input[name^="items["][name$="[rate]"]').value) || 0;
            const totalQuantity = parseFloat(row.querySelector('input[name^="items["][name$="[total_quantity]"]').value) || 0;
            const taxPercentage = parseFloat(row.querySelector('input[name^="items["][name$="[tax_percentage]"]').value) || 0;

            const itemGross = rate * totalQuantity;
            const itemDiscount = (itemGross / grossAmount) * discount;
            const itemNetGross = itemGross - itemDiscount;
            const itemNetTax = itemNetGross * (taxPercentage / 100);
            netTaxAmount += itemNetTax;
        });
    }

    const preRoundNetTotalAmount = netGrossAmount + netTaxAmount;
    const roundedNetTotalAmount = Math.round(preRoundNetTotalAmount);
    const roundOff = roundedNetTotalAmount - preRoundNetTotalAmount;

    document.getElementById('gross_amount').value = grossAmount.toFixed(2);
    document.getElementById('tax_amount').value = taxAmount.toFixed(2);
    document.getElementById('total_amount').value = totalAmount.toFixed(2);
    document.getElementById('net_gross_amount').value = netGrossAmount.toFixed(2);
    document.getElementById('net_tax_amount').value = netTaxAmount.toFixed(2);
    document.getElementById('round_off').value = roundOff.toFixed(2);
    document.getElementById('net_total_amount').value = roundedNetTotalAmount.toFixed(2);

    updatePaymentFields();
}

function updatePaymentFields() {
    const paymentOption = document.getElementById('payment_option').value;
    const netTotalAmount = parseFloat(document.getElementById('net_total_amount').value) || 0;
    const cashInput = document.getElementById('cash_amount');
    const upiInput = document.getElementById('upi_amount');
    const cardInput = document.getElementById('card_amount');
    const creditInput = document.getElementById('credit_amount');

    // Disable all inputs by default
    cashInput.disabled = true;
    upiInput.disabled = true;
    cardInput.disabled = true;
    creditInput.disabled = true;

    document.getElementById('sale_type').value = paymentOption;

    if (paymentOption === 'Cash') {
        cashInput.disabled = false;
        cashInput.value = netTotalAmount.toFixed(2);
        upiInput.value = '';
        cardInput.value = '';
        creditInput.value = '';
    } else if (paymentOption === 'UPI') {
        upiInput.disabled = false;
        cashInput.value = '';
        upiInput.value = netTotalAmount.toFixed(2);
        cardInput.value = '';
        creditInput.value = '';
    } else if (paymentOption === 'Card') {
        cardInput.disabled = false;
        cashInput.value = '';
        upiInput.value = '';
        cardInput.value = netTotalAmount.toFixed(2);
        creditInput.value = '';
    } else if (paymentOption === 'Credit') {
        creditInput.disabled = false;
        cashInput.value = '';
        upiInput.value = '';
        cardInput.value = '';
        creditInput.value = netTotalAmount.toFixed(2);
    } else if (paymentOption === 'Other') {
        cashInput.disabled = false;
        upiInput.disabled = false;
        cardInput.disabled = false;
        creditInput.disabled = false;
        cashInput.value = '';
        upiInput.value = '';
        cardInput.value = '';
        creditInput.value = '';
    }

    updateTotalPayment();
}

function updateTotalPayment() {
    const netTotalAmount = parseFloat(document.getElementById('net_total_amount').value) || 0;
    const cashAmount = parseFloat(document.getElementById('cash_amount').value) || 0;
    const upiAmount = parseFloat(document.getElementById('upi_amount').value) || 0;
    const cardAmount = parseFloat(document.getElementById('card_amount').value) || 0;
    const creditInput = document.getElementById('credit_amount');

    const nonCreditTotal = cashAmount + upiAmount + cardAmount;

    // If non-credit fields sum to less than net total, auto-fill credit with the remaining amount
    if (nonCreditTotal < netTotalAmount) {
        const remainingAmount = netTotalAmount - nonCreditTotal;
        creditInput.value = remainingAmount.toFixed(2);
    } else {
        creditInput.value = '';
    }

    const totalPayment = nonCreditTotal + (parseFloat(creditInput.value) || 0);
    document.getElementById('total_payment_amount').value = totalPayment.toFixed(2);

    // Check if total payment exceeds net total amount
    if (totalPayment > netTotalAmount + 0.01) {
        showPaymentErrorPopup();
    }
}
