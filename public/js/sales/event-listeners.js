$(document).ready(function() {
    let billCurrentFocus = -1;

    // Bill Search Input Handler
    $('#bill_search').on('input', function() {
        let query = $(this).val().trim();
        let suggestions = $('#bill_suggestions');
        suggestions.empty();
        billCurrentFocus = -1;

        if (query.length >= 2 && !$('#bill_search').prop('disabled')) {
            $.ajax({
                url: 'http://vanapp-sales.test/sales/search-bills',
                method: 'GET',
                data: {
                    query: query,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    suggestions.empty();
                    billCurrentFocus = -1;

                    if (response && response.length > 0) {
                        response.forEach(function(bill) {
                            suggestions.append(
                                `<div class="bill-item p-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100"
                                    data-id="${bill.id}"
                                    data-bill-no="${bill.bill_no}"
                                    data-date="${bill.sale_date}"
                                    data-customer-name="${bill.customer_name}"
                                    data-net-total="${bill.net_total_amount}">
                                    <div class="font-medium">${bill.bill_no}</div>
                                    <div class="text-sm text-gray-600">
                                        ${bill.customer_name} • ${bill.sale_date} • ₹${bill.net_total_amount}
                                    </div>
                                </div>`
                            );
                        });
                        suggestions.removeClass('hidden');
                    } else {
                        suggestions.append(
                            `<div class="p-2 text-gray-600">No matching bills found</div>`
                        );
                        suggestions.removeClass('hidden');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching bill data:', error, xhr.status, xhr.responseText);
                    suggestions.html(
                        `<div class="p-2 text-red-600">Error loading bills</div>`
                    );
                    suggestions.removeClass('hidden');
                }
            });
        } else {
            suggestions.addClass('hidden');
        }
    });

    // Handle Bill Selection (Click)
    $(document).on('click', '.bill-item', function() {
        const billId = $(this).data('id');
        const billNo = $(this).data('bill-no');

        $('#bill_search').val(billNo);
        $('#current_bill_id').val(billId);
        $('#bill_suggestions').addClass('hidden');

        $('#bill_search').prop('disabled', true);
        $('#clear_bill').removeClass('hidden');

        loadBillDetails(billId); // From bill-search.js
    });

    // Clear Bill Selection
    $('#clear_bill').on('click', function() {
        $('#current_bill_id').val('');
        $('#bill_search').val('');
        $('#bill_search').prop('disabled', false);
        $(this).addClass('hidden');
        clearForm(); // From bill-search.js
    });

    // Hide Suggestions on Outside Click
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#bill_search, #bill_suggestions').length) {
            $('#bill_suggestions').addClass('hidden');
        }
    });

    // Keyboard Navigation for Bill Suggestions
    $('#bill_search').on('keydown', function(e) {
        let suggestions = $('#bill_suggestions');
        let items = suggestions.find('.bill-item');

        if ($('#bill_search').prop('disabled')) {
            e.preventDefault();
            return;
        }

        if (e.keyCode == 40) { // Down arrow
            e.preventDefault();
            billCurrentFocus++;
            if (billCurrentFocus >= items.length) billCurrentFocus = 0;
            setActiveBill(items);
        } else if (e.keyCode == 38) { // Up arrow
            e.preventDefault();
            billCurrentFocus--;
            if (billCurrentFocus < 0) billCurrentFocus = items.length - 1;
            setActiveBill(items);
        } else if (e.keyCode == 13) { // Enter
            e.preventDefault();
            if (billCurrentFocus > -1 && items.length > 0) {
                items.eq(billCurrentFocus).trigger('click');
            }
        }
    });

    // Negative Stock Modal - Cancel Button
    $('#cancelNegativeStock').on('click', function() {
        $('#negativeStockModal').addClass('hidden');
        if (window.negativeStockPromiseReject) {
            window.negativeStockPromiseReject('User canceled negative stock confirmation');
        }
    });

    // Negative Stock Modal - Confirm Button
    $('#confirmNegativeStock').on('click', function() {
        $('#negativeStockModal').addClass('hidden');
        if (window.negativeStockPromiseResolve) {
            window.negativeStockPromiseResolve(true);
        }
    });

    function setActiveBill(items) {
        items.removeClass('bg-blue-100');
        if (billCurrentFocus >= 0 && billCurrentFocus < items.length) {
            items.eq(billCurrentFocus).addClass('bg-blue-100');
            items.eq(billCurrentFocus)[0].scrollIntoView({
                block: 'nearest',
                behavior: 'smooth'
            });
        }
    }
});
