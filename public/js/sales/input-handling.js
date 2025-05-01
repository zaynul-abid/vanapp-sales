$(document).ready(function() {
    const amountInputs = ['rate_input', 'tax_percentage_input', 'discount', 'cash_amount', 'upi_amount', 'card_amount', 'credit_amount'];
    amountInputs.forEach(id => {
        $('#' + id).on('focus', function() {
            if (this.value === '0.00') {
                this.value = '';
            }
        }).on('blur', function() {
            if (!this.value) {
                this.value = '';
            }
        });
    });
});
