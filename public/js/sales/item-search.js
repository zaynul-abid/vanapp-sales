$(document).ready(function() {
    $('body').append('<div id="item_suggestions" class="hidden absolute z-10 mt-1 bg-white shadow-lg rounded-md border border-gray-300 max-h-60 overflow-auto" style="width: 300px;"></div>');

    $('#item_name_input').on('focus', function() {
        const inputRect = this.getBoundingClientRect();
        $('#item_suggestions').css({
            'top': inputRect.bottom + window.scrollY + 'px',
            'left': inputRect.left + window.scrollX + 'px',
            'width': inputRect.width + 'px'
        });
    });

    let itemCurrentFocus = -1;

    $('#item_name_input').on('input', function() {
        let query = $(this).val().trim();
        let suggestions = $('#item_suggestions');
        suggestions.empty();
        itemCurrentFocus = -1;

        if (query.length >= 2) {
            $.ajax({
                url: '/search-items',
                method: 'GET',
                data: { query: query },
                dataType: 'json',
                success: function(response) {
                    suggestions.empty();
                    itemCurrentFocus = -1;

                    if (Array.isArray(response) && response.length > 0) {
                        response.forEach(function(item) {
                            suggestions.append(
                                `<div class="item-suggestion p-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100"
                                    data-id="${item.id}"
                                    data-name="${item.name}"
                                    data-retail="${item.retail_price}"
                                    data-wholesale="${item.wholesale_price}"
                                    data-unit="${item.unit_name}"
                                    data-unit-quantity="${item.quantity || 1}"
                                    data-tax="${item.tax_percentage}"
                                    data-stock="${item.stock || 0}">
                                    <div class="font-medium">${item.name}</div>
                                    <div class="text-sm text-gray-600">
                                        ${item.unit_name} |
                                        Retail: ₹${item.retail_price} |
                                        Wholesale: ₹${item.wholesale_price} |
                                        Unit Qty: ${item.unit_quantity || 1} |
                                        Tax: ${item.tax_percentage}% |
                                        Stock: ${item.stock || 0}
                                    </div>
                                </div>`
                            );
                        });
                        suggestions.removeClass('hidden');
                    } else {
                        suggestions.append(
                            `<div class="p-2 text-gray-600">No matching items found</div>`
                        );
                        suggestions.removeClass('hidden');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching item data:', error);
                    suggestions.append(
                        `<div class="p-2 text-red-600">Error fetching items</div>`
                    );
                    suggestions.removeClass('hidden');
                }
            });
        } else {
            suggestions.addClass('hidden');
        }
    });

    $('#item_name_input').on('keydown', function(e) {
        let suggestions = $('#item_suggestions');
        let items = suggestions.find('.item-suggestion');

        if (e.keyCode == 40) { // Down arrow
            e.preventDefault();
            itemCurrentFocus++;
            if (itemCurrentFocus >= items.length) itemCurrentFocus = 0;
            setActiveItem(items);
        } else if (e.keyCode == 38) { // Up arrow
            e.preventDefault();
            itemCurrentFocus--;
            if (itemCurrentFocus < 0) itemCurrentFocus = items.length - 1;
            setActiveItem(items);
        } else if (e.keyCode == 13) { // Enter
            e.preventDefault();
            if (itemCurrentFocus > -1 && items.length > 0) {
                selectItem(items.eq(itemCurrentFocus));
            }
        }
    });

    function setActiveItem(items) {
        items.removeClass('bg-blue-100');
        if (itemCurrentFocus >= 0 && itemCurrentFocus < items.length) {
            items.eq(itemCurrentFocus).addClass('bg-blue-100');
            items.eq(itemCurrentFocus)[0].scrollIntoView({
                block: 'nearest',
                behavior: 'smooth'
            });
        }
    }

    function selectItem(item) {
        $('#item_name_input').val(item.data('name'));
        $('#item_id_input').val(item.data('id'));
        $('#rate_input').val(item.data('retail'));
        $('#unit_input').val(item.data('unit') || 'Cartoon');
        $('#unit_quantity_input').val(item.data('unit-quantity'));
        $('#tax_percentage_input').val(item.data('tax'));
        $('#stock_input').val(item.data('stock'));
        $('#rate_input').data('retail', item.data('retail'));
        $('#rate_input').data('wholesale', item.data('wholesale'));
        $('#custom_quantity_input').val('');
        $('#total_quantity_input').val('');
        $('#item_suggestions').addClass('hidden');
        itemCurrentFocus = -1;
        $('#custom_quantity_input').focus();
        updateTotalQuantity();
    }

    $(document).on('click', '#item_suggestions .item-suggestion', function() {
        selectItem($(this));
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#item_name_input, #item_suggestions').length) {
            $('#item_suggestions').addClass('hidden');
        }
    });
});
