<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Entry - Van App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        #customer_suggestions {
            width: auto; /* Adjusts to content width */
            min-width: 100%; /* But at least as wide as input */
        }

        #customer_suggestions div {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }

        #customer_suggestions div:hover, #customer_suggestions div.highlighted {
            background-color: #f0f7ff;
        }

        .customer-details {
            color: #666;
            font-size: 0.9em;
        }
    </style>

</head>

<body class="bg-gray-100 p-6">
<div class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Sale Entry</h1>

    <form action="{{route('sales.store')}}" method="POST" onsubmit="return validateForm()">
        @csrf
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">


        <!-- Sale Master Section -->
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Sale Master</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label for="sale_date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" id="sale_date"  value="{{ now()->format('Y-m-d') }}" name="sale_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="sale_time" class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                <input type="time" id="sale_time" name="sale_time" value="{{ now()->format('H:i') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 relative">
            <div class="relative">
                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                <input type="hidden" id="customer_id" name="customer_id" value="">
                <input type="text" id="customer_name" name="customer_name"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required autocomplete="off">
                <div id="customer_suggestions" class="hidden absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-300 max-h-60 overflow-auto"></div>
            </div>
            <div>
                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-1">Customer Address</label>
                <textarea id="customer_address" name="customer_address"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          rows="3"></textarea>
            </div>
        </div>

        <hr class="my-8">

        <!-- Sale Details Section -->
        <h2 class="text-2xl font-semibold text-gray-700 mb-7">Sale Details</h2>
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Add Item</h3>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
                <div>
                    <label for="item_name_input" class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                    <input type="text" id="item_name_input" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" >
                </div>
                <div>
                    <label for="item_id_input" class="block text-sm font-medium text-gray-700 mb-1">Item ID</label>
                    <input type="text" id="item_id_input" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" >
                </div>

                <div>
                    <label for="rate_input" class="block text-sm font-medium text-gray-700 mb-1">Rate</label>
                    <input type="number" id="rate_input" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" >
                </div>
                <div>
                    <label for="quantity_input" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number" id="quantity_input" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" >
                </div>
                <div>
                    <label for="unit_input" class="block text-sm font-medium text-gray-700 mb-1">unit</label>
                    <input type="text" id="unit_input" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" >
                </div>
                <div>
                    <label for="tax_percentage_input" class="block text-sm font-medium text-gray-700 mb-1">Tax %</label>
                    <input type="number" id="tax_percentage_input" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="0.00">
                </div>
                <div>
                    <button type="button" onclick="addItemToTable()" class="mt-6 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add</button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="item-table" class="min-w-full bg-white border border-gray-300">
                <thead>
                <tr class="bg-gray-100">


                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Item ID</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Item Name</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Rate</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Quantity</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Unit </th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Tax %</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Total Amount</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Actions</th>
                </tr>
                </thead>
                <tbody id="item-rows"></tbody>
            </table>
        </div>

        <!-- Amount Details -->
        <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">Amount Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div>
                <label for="gross_amount" class="block text-sm font-medium text-gray-700 mb-1">Gross Amount</label>
                <input type="number" id="gross_amount" name="gross_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
            </div>
            <div>
                <label for="tax_amount" class="block text-sm font-medium text-gray-700 mb-1">Tax Amount</label>
                <input type="number" id="tax_amount" name="tax_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
            </div>
            <div>
                <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                <input type="number" id="total_amount" name="total_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
            </div>
            <div>
                <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Discount</label>
                <input type="number" id="discount" name="discount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="0.00" oninput="updateTotals()">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label for="net_gross_amount" class="block text-sm font-medium text-gray-700 mb-1">Net Gross Amount</label>
                <input type="number" id="net_gross_amount" name="net_gross_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
            </div>
            <div>
                <label for="net_tax_amount" class="block text-sm font-medium text-gray-700 mb-1">Net Tax Amount</label>
                <input type="number" id="net_tax_amount" name="net_tax_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
            </div>
            <div>
                <label for="net_total_amount" class="block text-sm font-medium text-gray-700 mb-1">Net Total Amount</label>
                <input type="number" id="net_total_amount" name="net_total_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
            </div>
        </div>

        <!-- Payment Details -->
        <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-6">Payment Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label for="payment_option" class="block text-sm font-medium text-gray-700 mb-1">Payment Option</label>
                <select id="payment_option" name="payment_option" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="updatePaymentFields()">
                    <option value="Cash">Cash</option>
                    <option value="Credit">Credit</option>
                    <option value="UPI">UPI</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label for="cash_amount" class="block text-sm font-medium text-gray-700 mb-1">Cash</label>
                <input type="number" id="cash_amount" name="cash_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="0.00" oninput="updateTotalPayment()">
            </div>
            <div>
                <label for="credit_amount" class="block text-sm font-medium text-gray-700 mb-1">Credit</label>
                <input type="number" id="credit_amount" name="credit_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="0.00" oninput="updateTotalPayment()">
            </div>
            <div>
                <label for="upi_amount" class="block text-sm font-medium text-gray-700 mb-1">UPI</label>
                <input type="number" id="upi_amount" name="upi_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="0.00" oninput="updateTotalPayment()">
            </div>
        </div>
        <div class="mt-4">
            <label for="total_payment_amount" class="block text-sm font-medium text-gray-700 mb-1">Total Payment Amount</label>
            <input type="number" id="total_payment_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
        </div>
        <input type="hidden" id="sale_type" name="sale_type" value="Cash">

        <!-- Narration -->
        <div class="mb-6 mt-6">
            <label for="narration" class="block text-sm font-medium text-gray-700 mb-1">Narration</label>
            <textarea id="narration" name="narration" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4"></textarea>
        </div>

        <!-- Submit Button -->
        <div class="mt-8">
            <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded hover:bg-green-600">Save Sale</button>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script>
    // ITEM SEARCH FUNCTIONALITY - START
    $(document).ready(function() {
        // Create suggestions container for items
        $('body').append('<div id="item_suggestions" class="hidden absolute z-10 mt-1 bg-white shadow-lg rounded-md border border-gray-300 max-h-60 overflow-auto" style="width: 300px;"></div>');

        // Position the suggestions box below the item name input
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
            if (query.length >= 2) {
                $.ajax({
                    url: '/search-items',
                    method: 'GET',
                    data: { query: query },
                    dataType: 'json',
                    success: function(response) {
                        let suggestions = $('#item_suggestions');
                        suggestions.empty();
                        itemCurrentFocus = -1;

                        if (response.length > 0) {
                            response.forEach(function(item) {
                                suggestions.append(
                                    `<div class="item-suggestion p-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100"
                                        data-id="${item.id}"
                                        data-name="${item.name}"
                                        data-retail="${item.retail_price}"
                                        data-wholesale="${item.wholesale_price}"
                                        data-unit="${item.unit_name}"
                                        data-tax="${item.tax_percentage}">
                                        <div class="font-medium">${item.name}</div>
                                        <div class="text-sm text-gray-600">
                                            ${item.unit_name} |
                                            Retail: ₹${item.retail_price} |
                                            Wholesale: ₹${item.wholesale_price} |
                                            Tax: ${item.tax_percentage}%
                                        </div>
                                    </div>`
                                );
                            });
                            suggestions.removeClass('hidden');
                        } else {
                            suggestions.addClass('hidden');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching item data:', error);
                    }
                });
            } else {
                $('#item_suggestions').addClass('hidden');
            }
        });

        // Handle keyboard navigation for items
        $('#item_name_input').keydown(function(e) {
            let suggestions = $('#item_suggestions');
            let items = suggestions.find('.item-suggestion');

            if (e.keyCode == 40) { // Down arrow
                itemCurrentFocus++;
                if (itemCurrentFocus >= items.length) itemCurrentFocus = 0;
                setActiveItem(items);
                e.preventDefault();
            } else if (e.keyCode == 38) { // Up arrow
                itemCurrentFocus--;
                if (itemCurrentFocus < 0) itemCurrentFocus = items.length - 1;
                setActiveItem(items);
                e.preventDefault();
            } else if (e.keyCode == 13) { // Enter
                if (itemCurrentFocus > -1) {
                    e.preventDefault();
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
            $('#rate_input').val(item.data('retail')); // Default to retail price
            $('#unit_input').val(item.data('unit'));
            $('#tax_percentage_input').val(item.data('tax'));
            // Store both prices in data attributes
            $('#rate_input').data('retail', item.data('retail'));
            $('#rate_input').data('wholesale', item.data('wholesale'));
            $('#item_suggestions').addClass('hidden');
            itemCurrentFocus = -1;
            $('#quantity_input').focus();
        }

        // Handle mouse selection for items
        $(document).on('click', '#item_suggestions .item-suggestion', function() {
            selectItem($(this));
        });

        // Hide item suggestions when clicking elsewhere
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#item_name_input, #item_suggestions').length) {
                $('#item_suggestions').addClass('hidden');
            }
        });
    });
    // ITEM SEARCH FUNCTIONALITY - END

    // CUSTOMER SEARCH FUNCTIONALITY - START
    $(document).ready(function() {
        let currentFocus = -1;

        $('#customer_name').on('input', function() {
            let query = $(this).val().trim();
            if (query.length >= 2) {
                $.ajax({
                    url: '/search-customers',
                    method: 'GET',
                    data: { query: query },
                    dataType: 'json',
                    success: function(response) {
                        let suggestions = $('#customer_suggestions');
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
                $('#customer_suggestions').addClass('hidden');
            }
        });

        // Handle keyboard navigation
        $('#customer_name').keydown(function(e) {
            let suggestions = $('#customer_suggestions');
            let items = suggestions.find('.customer-item');

            if (e.keyCode == 40) { // Down arrow
                currentFocus++;
                if (currentFocus >= items.length) currentFocus = 0;
                setActive(items);
                e.preventDefault();
            } else if (e.keyCode == 38) { // Up arrow
                currentFocus--;
                if (currentFocus < 0) currentFocus = items.length - 1;
                setActive(items);
                e.preventDefault();
            } else if (e.keyCode == 13) { // Enter
                if (currentFocus > -1) {
                    e.preventDefault();
                    selectCustomer(items.eq(currentFocus));
                }
            }
        });

        function setActive(items) {
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
            console.log('Selected customer ID:', item.data('id'));
            console.log('Selected customer ID:', item.data('name'));
            $('#customer_name').val(item.data('name'));
            $('#customer_id').val(item.data('id'));
            $('#customer_address').val(item.data('address'));
            $('#customer_suggestions').addClass('hidden');
            currentFocus = -1;
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
    // CUSTOMER SEARCH FUNCTIONALITY - END

    function addItemToTable() {
        const itemId = document.getElementById('item_id_input').value;
        const itemName = document.getElementById('item_name_input').value;
        let rate = parseFloat(document.getElementById('rate_input').value) || 0;
        const quantity = parseFloat(document.getElementById('quantity_input').value) || 0;
        const unit = document.getElementById('unit_input').value || '';
        const taxPercentage = parseFloat(document.getElementById('tax_percentage_input').value) || 0;
        const retailPrice = parseFloat($('#rate_input').data('retail')) || 0;
        const wholesalePrice = parseFloat($('#rate_input').data('wholesale')) || 0;

        if (!itemId || !itemName || rate <= 0 || quantity <= 0 || !unit) {
            alert('Please fill in all required item fields with valid values.');
            return;
        }

        const priceType = rate === retailPrice ? 'Retail' : 'Wholesale';

        const grossAmount = rate * quantity;
        const taxAmount = grossAmount * (taxPercentage / 100);
        const totalAmount = grossAmount + taxAmount;

        const row = `
            <tr class="item-row">
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[][item_id]" value="${itemId}">${itemId}
                </td>
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[][item_name]" value="${itemName}">${itemName}
                </td>
                <td class="py-2 px-4 border-b relative">
                    <div class="rate-display">${rate.toFixed(2)}</div>
                    <div class="price-type-selector hidden absolute bg-white border border-gray-300 shadow-lg z-10 mt-1 rounded" style="width: 200px;">
                        <div class="p-2 hover:bg-blue-50 cursor-pointer" data-price="${retailPrice}">Retail: ${retailPrice.toFixed(2)}</div>
                        <div class="p-2 hover:bg-blue-50 cursor-pointer" data-price="${wholesalePrice}">Wholesale: ${wholesalePrice.toFixed(2)}</div>
                    </div>
                    <input type="hidden" name="items[][rate]" value="${rate.toFixed(2)}">
                    <input type="hidden" name="items[][price_type]" value="${priceType}">
                </td>
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[][quantity]" value="${quantity.toFixed(2)}">${quantity.toFixed(2)}
                </td>
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[][unit]" value="${unit}">${unit}
                </td>
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[][tax_percentage]" value="${taxPercentage.toFixed(2)}">${taxPercentage.toFixed(2)}
                </td>
                <td class="py-2 px-4 border-b">
                    <input type="hidden" name="items[][total_amount]" value="${totalAmount.toFixed(2)}">${totalAmount.toFixed(2)}
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
            row.find('input[name="items[][rate]"]').val(newPrice.toFixed(2));
            row.find('input[name="items[][price_type]"]').val($(this).text().includes('Retail') ? 'Retail' : 'Wholesale');
            priceSelector.addClass('hidden');
            updateTotals();
        });

        // Clear input fields
        document.getElementById('item_id_input').value = '';
        document.getElementById('item_name_input').value = '';
        document.getElementById('rate_input').value = '';
        document.getElementById('quantity_input').value = '';
        document.getElementById('unit_input').value = '';
        document.getElementById('tax_percentage_input').value = '0.00';

        updateTotals();
    }

    // Close price selector when clicking elsewhere
    $(document).on('click', function() {
        $('.price-type-selector').addClass('hidden');
    });

    function removeItemRow(button) {
        button.closest('.item-row').remove();
        updateTotals();
    }

    function updateTotals() {
        const rows = document.querySelectorAll('.item-row');
        let grossAmount = 0;
        let taxAmount = 0;

        // Calculate Gross Amount and Tax Amount before discount
        rows.forEach(row => {
            const rate = parseFloat(row.querySelector('input[name="items[][rate]"]').value) || 0;
            const quantity = parseFloat(row.querySelector('input[name="items[][quantity]"]').value) || 0;
            const taxPercentage = parseFloat(row.querySelector('input[name="items[][tax_percentage]"]').value) || 0;

            const itemGross = rate * quantity;
            const itemTax = itemGross * (taxPercentage / 100);
            grossAmount += itemGross;
            taxAmount += itemTax;
        });

        const totalAmount = grossAmount + taxAmount;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const netGrossAmount = grossAmount - discount;

        // Recalculate tax after discount by distributing discount proportionally
        let netTaxAmount = 0;
        if (grossAmount > 0) { // Avoid division by zero
            rows.forEach(row => {
                const rate = parseFloat(row.querySelector('input[name="items[][rate]"]').value) || 0;
                const quantity = parseFloat(row.querySelector('input[name="items[][quantity]"]').value) || 0;
                const taxPercentage = parseFloat(row.querySelector('input[name="items[][tax_percentage]"]').value) || 0;

                const itemGross = rate * quantity;
                // Proportional discount for this item
                const itemDiscount = (itemGross / grossAmount) * discount;
                const itemNetGross = itemGross - itemDiscount;
                const itemNetTax = itemNetGross * (taxPercentage / 100);
                netTaxAmount += itemNetTax;
            });
        }

        const netTotalAmount = netGrossAmount + netTaxAmount;

        document.getElementById('gross_amount').value = grossAmount.toFixed(2);
        document.getElementById('tax_amount').value = taxAmount.toFixed(2);
        document.getElementById('total_amount').value = totalAmount.toFixed(2);
        document.getElementById('net_gross_amount').value = netGrossAmount.toFixed(2);
        document.getElementById('net_tax_amount').value = netTaxAmount.toFixed(2);
        document.getElementById('net_total_amount').value = netTotalAmount.toFixed(2);

        updatePaymentFields();
    }

    function updatePaymentFields() {
        const paymentOption = document.getElementById('payment_option').value;
        const netTotalAmount = parseFloat(document.getElementById('net_total_amount').value) || 0;
        const cashInput = document.getElementById('cash_amount');
        const creditInput = document.getElementById('credit_amount');
        const upiInput = document.getElementById('upi_amount');

        document.getElementById('sale_type').value = paymentOption;

        if (paymentOption === 'Cash') {
            cashInput.value = netTotalAmount.toFixed(2);
            creditInput.value = '0.00';
            upiInput.value = '0.00';
        } else if (paymentOption === 'Credit') {
            cashInput.value = '0.00';
            creditInput.value = netTotalAmount.toFixed(2);
            upiInput.value = '0.00';
        } else if (paymentOption === 'UPI') {
            cashInput.value = '0.00';
            creditInput.value = '0.00';
            upiInput.value = netTotalAmount.toFixed(2);
        } else if (paymentOption === 'Other') {
            cashInput.value = '0.00';
            creditInput.value = '0.00';
            upiInput.value = '0.00';
        }

        updateTotalPayment();
    }

    function updateTotalPayment() {
        const cashAmount = parseFloat(document.getElementById('cash_amount').value) || 0;
        const creditAmount = parseFloat(document.getElementById('credit_amount').value) || 0;
        const upiAmount = parseFloat(document.getElementById('upi_amount').value) || 0;

        const totalPayment = cashAmount + creditAmount + upiAmount;
        document.getElementById('total_payment_amount').value = totalPayment.toFixed(2);
    }

    function validateForm() {
        const netTotalAmount = parseFloat(document.getElementById('net_total_amount').value) || 0;
        const totalPayment = parseFloat(document.getElementById('total_payment_amount').value) || 0;

        if (Math.abs(netTotalAmount - totalPayment) > 0.01) {
            alert('Total payment amount must equal the net total amount.');
            return false;
        }

        return true;
    }
</script>
</body>
</html>
