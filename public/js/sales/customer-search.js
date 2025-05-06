$(document).ready(function() {
    let currentFocus = -1;

    $('#customer_name').on('input', function() {
        let query = $(this).val().trim();
        let suggestions = $('#customer_suggestions');
        suggestions.empty();
        currentFocus = -1;

        if (query.length >= 2) {
            $.ajax({
                url: '/search-customers',
                method: 'GET',
                data: { query: query },
                dataType: 'json',
                success: function(response) {
                    suggestions.empty();
                    currentFocus = -1;

                    if (response.length > 0) {
                        response.forEach(function(customer) {
                            suggestions.append(
                                `<div class="customer-item"
                                    data-id="${customer.id}"
                                    data-name="${customer.name}"
                                    data-address="${customer.address}"
                                    data-phone="${customer.phone}"
                                    data-email="${customer.email}">
                                    <span>${customer.name}</span>
                                    <span class="customer-details">
                                        ${customer.phone} • ${customer.email}
                                    </span>
                                </div>`
                            );
                        });
                        suggestions.removeClass('hidden');
                    } else {
                        suggestions.addClass('hidden');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching customer data:', error);
                }
            });
        } else {
            suggestions.addClass('hidden');
        }
    });

    // Handle keyboard navigation for customers
    $('#customer_name').on('keydown', function(e) {
        let suggestions = $('#customer_suggestions');
        let items = suggestions.find('.customer-item');

        if (e.keyCode == 40) { // Down arrow
            e.preventDefault();
            currentFocus++;
            if (currentFocus >= items.length) currentFocus = 0;
            setActiveCustomer(items);
        } else if (e.keyCode == 38) { // Up arrow
            e.preventDefault();
            currentFocus--;
            if (currentFocus < 0) currentFocus = items.length - 1;
            setActiveCustomer(items);
        } else if (e.keyCode == 13) { // Enter
            e.preventDefault();
            if (currentFocus > -1 && items.length > 0) {
                selectCustomer(items.eq(currentFocus));
            } else {
                $('#item_name_input').focus();
            }
        }
    });

    function setActiveCustomer(items) {
        items.removeClass('highlighted');
        if (currentFocus >= 0 && currentFocus < items.length) {
            items.eq(currentFocus).addClass('highlighted');
            items.eq(currentFocus)[0].scrollIntoView({
                block: 'nearest',
                behavior: 'smooth'
            });
        }
    }

    function selectCustomer(item) {
        $('#customer_name').val(item.data('name'));
        $('#customer_id').val(item.data('id'));
        $('#customer_address').val(item.data('address'));
        $('#customer_suggestions').addClass('hidden');
        currentFocus = -1;
        $('#item_name_input').focus();
    }

    // Handle mouse selection
    $(document).on('click', '#customer_suggestions .customer-item', function() {
        selectCustomer($(this));
    });

    // Hide suggestions when clicking elsewhere
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#customer_name, #customer_suggestions').length) {
            $('#customer_suggestions').addClass('hidden');
        }
    });
});
