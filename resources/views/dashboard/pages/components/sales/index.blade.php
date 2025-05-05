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
            width: auto;
            min-width: 100%;
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
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Sale Entry</h1>
        <a href="{{route('employee.dashboard')}}"
           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out"
           aria-label="Back to Employee Dashboard">
            Back
        </a>
    </div>

    <div id="message-section">
        @if(session('success'))
            <div class="flex items-center justify-between p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                <div>
                    <strong class="font-bold">Success! </strong> {{ session('success') }}
                </div>
                <button type="button" class="text-green-700 hover:text-green-900" onclick="this.parentElement.remove()" aria-label="Close">
                    ✖
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center justify-between p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <div>
                    <strong class="font-bold">Error! </strong> {{ session('error') }}
                </div>
                <button type="button" class="text-red-700 hover:text-red-900" onclick="this.parentElement.remove()" aria-label="Close">
                    ✖
                </button>
            </div>
        @endif

        <div id="client-error-messages"></div>

        <div class="mb-6">
            <div class="flex items-center gap-4">
                <div class="flex-1 relative">
                    <label for="bill_search" class="block text-sm font-medium text-gray-700 mb-1">Search Bill</label>
                    <input type="text" id="bill_search" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by bill no or customer name">
                    <div id="bill_suggestions" class="hidden absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-300 max-h-60 overflow-auto"></div>
                </div>
                <div class="mt-5">
                    <button type="button" id="clear_bill" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 hidden">Clear</button>
                </div>
            </div>
            <input type="hidden" id="current_bill_id" name="current_bill_id" value="">
        </div>
    </div>

    <form action="{{route('sales.store')}}" method="POST" onsubmit="return validateForm()">
        @csrf

        <!-- Sale Master Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label for="sale_date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" id="sale_date" value="{{ now()->format('Y-m-d') }}" name="sale_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onkeydown="preventEnter(event)" required>
            </div>
            <div>
                <label for="sale_time" class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                <input type="time" id="sale_time" name="sale_time" value="{{ now()->format('H:i') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onkeydown="preventEnter(event)" required>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 relative">
            <div class="relative">
                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                <input type="hidden" id="customer_id" name="customer_id" value="">
                <input type="text" id="customer_name" name="customer_name"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       onkeydown="handleCustomerEnter(event)" required autocomplete="off">
                <div id="customer_suggestions" class="hidden absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-300 max-h-60 overflow-auto"></div>
            </div>
            <div>
                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-1">Customer Address</label>
                <textarea id="customer_address" name="customer_address"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          rows="3" onkeydown="preventEnter(event)"></textarea>
            </div>
        </div>

        <hr class="my-8">

        <!-- Sale Details Section -->
        <h2 class="text-2xl font-semibold text-gray-700 mb-7">Item Details</h2>
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Add Item</h3>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
                <div>
                    <label for="item_name_input" class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                    <input type="text" id="item_name_input" class="min-w-0 flex-grow w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onkeydown="preventEnter(event)">
                </div>
                <div>
                    <label for="item_id_input" class="block text-sm font-medium text-gray-700 mb-1">Item ID</label>
                    <input type="text" id="item_id_input" class="min-w-0 flex-grow w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onkeydown="preventEnter(event)">
                </div>
                <div>
                    <label for="rate_input" class="block text-sm font-medium text-gray-700 mb-1">Rate</label>
                    <input type="number" id="rate_input" step="0.01" class="min-w-0 flex-grow w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onkeydown="preventEnter(event)">
                </div>
                <div>
                    <label for="unit_input" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <input type="text" id="unit_input" class="min-w-0 flex-grow w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                </div>
                <div>
                    <label for="unit_quantity_input" class="block text-sm font-medium text-gray-700 mb-1">Unit Quantity</label>
                    <input type="number" id="unit_quantity_input" step="0.01" class="min-w-0 flex-grow w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                </div>
                <div>
                    <label for="custom_quantity_input" class="block text-sm font-medium text-gray-700 mb-1">Custom Quantity</label>
                    <input type="number" id="custom_quantity_input" step="0.01" class="min-w-0 flex-grow w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="updateTotalQuantity()" onkeydown="handleItemEnter(event)">
                </div>
                <div>
                    <label for="total_quantity_input" class="block text-sm font-medium text-gray-700 mb-1">Total Quantity</label>
                    <input type="number" id="total_quantity_input" step="0.01" class="min-w-0 flex-grow w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                </div>
                <div>
                    <label for="tax_percentage_input" class="block text-sm font-medium text-gray-700 mb-1">Tax %</label>
                    <input type="number" id="tax_percentage_input" step="0.01" class="min-w-0 flex-grow w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onkeydown="preventEnter(event)">
                </div>
                <div>
                    <label for="stock_input" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" id="stock_input" step="0.01" class="min-w-0 flex-grow w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button type="button" onclick="addItemToTable()" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add</button>
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
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Unit</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Unit Quantity</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Custom Quantity</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Total Quantity</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Tax %</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Stock</th>
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
                <input type="number" id="discount" name="discount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="updateTotals()" onkeydown="preventEnter(event)">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div>
                <label for="net_gross_amount" class="block text-sm font-medium text-gray-700 mb-1">Net Gross Amount</label>
                <input type="number" id="net_gross_amount" name="net_gross_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
            </div>
            <div>
                <label for="net_tax_amount" class="block text-sm font-medium text-gray-700 mb-1">Net Tax Amount</label>
                <input type="number" id="net_tax_amount" name="net_tax_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
            </div>
            <div>
                <label for="round_off" class="block text-sm font-medium text-gray-700 mb-1">Round Off</label>
                <input type="number" id="round_off" name="round_off" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
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
                    <option value="UPI">UPI</option>
                    <option value="Card">Card</option>
                    <option value="Credit">Credit</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div>
                <label for="cash_amount" class="block text-sm font-medium text-gray-700 mb-1">Cash</label>
                <input type="number" id="cash_amount" name="cash_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="updateTotalPayment()" onkeydown="handlePaymentEnter(event, 'cash_amount')">
            </div>
            <div>
                <label for="upi_amount" class="block text-sm font-medium text-gray-700 mb-1">UPI</label>
                <input type="number" id="upi_amount" name="upi_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="updateTotalPayment()" onkeydown="handlePaymentEnter(event, 'upi_amount')">
            </div>
            <div>
                <label for="card_amount" class="block text-sm font-medium text-gray-700 mb-1">Card</label>
                <input type="number" id="card_amount" name="card_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="updateTotalPayment()" onkeydown="handlePaymentEnter(event, 'card_amount')">
            </div>
            <div>
                <label for="credit_amount" class="block text-sm font-medium text-gray-700 mb-1">Credit</label>
                <input type="number" id="credit_amount" name="credit_amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="updateTotalPayment()" onkeydown="handlePaymentEnter(event, 'credit_amount')">
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
            <textarea id="narration" name="narration" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" onkeydown="preventEnter(event)"></textarea>
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
    // Function to prevent Enter key from submitting the form
    function preventEnter(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
        }
    }

    // Function to display client-side error messages
    function showErrorMessage(message) {
        const errorContainer = document.getElementById('client-error-messages');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'flex items-center justify-between p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg';
        errorDiv.setAttribute('role', 'alert');
        errorDiv.innerHTML = `
            <div>
                <strong class="font-bold">Error! </strong> ${message}
            </div>
            <button type="button" class="text-red-700 hover:text-red-900" onclick="this.parentElement.remove()" aria-label="Close">
                ✖
            </button>
        `;
        errorContainer.appendChild(errorDiv);
    }

    // Function to clear all client-side error messages
    function clearErrorMessages() {
        const errorContainer = document.getElementById('client-error-messages');
        errorContainer.innerHTML = '';
    }

    // CUSTOMER SEARCH FUNCTIONALITY
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

    // ITEM SEARCH FUNCTIONALITY
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

        // Handle keyboard navigation for items
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

    // Update total quantity display
    function updateTotalQuantity() {
        const unitQuantity = parseFloat(document.getElementById('unit_quantity_input').value) || 0;
        const customQuantity = parseFloat(document.getElementById('custom_quantity_input').value) || 0;
        const totalQuantity = unitQuantity * customQuantity;
        document.getElementById('total_quantity_input').value = totalQuantity.toFixed(2);
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

        // Optional: Validate stock
        if (totalQuantity > stock) {
            showErrorMessage('Total quantity exceeds available stock.');
            return;
        }

        const priceType = rate === retailPrice ? 'Retail' : rate === wholesalePrice ? 'Wholesale' : 'Custom';
        const grossAmount = rate * totalQuantity;
        const taxAmount = grossAmount * (taxPercentage / 100);
        const totalAmount = grossAmount + taxAmount;

        // Check if item with same item_id, rate, and unit already exists
        let existingRow = null;
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(row => {
            const rowItemId = row.querySelector('input[name$="[item_id]"]').value;
            const rowRate = parseFloat(row.querySelector('input[name$="[rate]"]').value) || 0;
            const rowUnit = row.querySelector('input[name$="[unit]"]').value;
            if (rowItemId === itemId && rowRate === rate && rowUnit === unit) {
                existingRow = row;
            }
        });

        if (existingRow) {
            // Update existing row
            const existingCustomQuantityInput = existingRow.querySelector('input[name$="[custom_quantity]"]');
            const existingTotalQuantityInput = existingRow.querySelector('input[name$="[total_quantity]"]');
            const existingGrossAmountInput = existingRow.querySelector('input[name$="[gross_amount]"]');
            const existingTaxAmountInput = existingRow.querySelector('input[name$="[tax_amount]"]');
            const existingTotalAmountInput = existingRow.querySelector('input[name$="[total_amount]"]');

            // Update quantities
            const existingCustomQuantity = parseFloat(existingCustomQuantityInput.value) || 0;
            const newCustomQuantity = existingCustomQuantity + customQuantity;
            const newTotalQuantity = newCustomQuantity * unitQuantity;

            // Validate stock for updated quantity
            if (newTotalQuantity > stock) {
                showErrorMessage('Updated total quantity exceeds available stock.');
                return;
            }

            // Update gross and tax amounts
            const newGrossAmount = rate * newTotalQuantity;
            const newTaxAmount = newGrossAmount * (taxPercentage / 100);
            const newTotalAmount = newGrossAmount + newTaxAmount;

            // Update DOM elements
            existingCustomQuantityInput.value = newCustomQuantity.toFixed(2);
            existingRow.querySelector('td:nth-child(6)').textContent = newCustomQuantity.toFixed(2); // Update visible custom quantity
            existingTotalQuantityInput.value = newTotalQuantity.toFixed(2);
            existingRow.querySelector('td:nth-child(7)').textContent = newTotalQuantity.toFixed(2); // Update visible total quantity
            existingGrossAmountInput.value = newGrossAmount.toFixed(2);
            existingTaxAmountInput.value = newTaxAmount.toFixed(2);
            existingTotalAmountInput.value = newTotalAmount.toFixed(2);
            existingRow.querySelector('td:nth-child(10)').textContent = newTotalAmount.toFixed(2); // Update visible total amount
        } else {
            // Add new row
            const rowCount = $('#item-rows tr').length;

            const row = `
                <tr class="item-row">
                    <td class="py-2 px-4 border-b">
                        <input type="hidden" name="items[${rowCount}][item_id]" value="${itemId}">${itemId}
                    </td>
                    <td class="py-2 px-4 border-b">
                        <input type="hidden" name="items[${rowCount}][item_name]" value="${itemName}">${itemName}
                    </td>
                    <td class="py-2 px-4 border-b relative">
                        <div class="rate-display">${rate.toFixed(2)}</div>
                        <div class="price-type-selector hidden absolute bg-white border border-gray-300 shadow-lg z-10 mt-1 rounded" style="width: 200px;">
                            <div class="p-2 hover:bg-blue-50 cursor-pointer" data-price="${retailPrice}">Retail: ${retailPrice.toFixed(2)}</div>
                            <div class="p-2 hover:bg-blue-50 cursor-pointer" data-price="${wholesalePrice}">Wholesale: ${wholesalePrice.toFixed(2)}</div>
                        </div>
                        <input type="hidden" name="items[${rowCount}][rate]" value="${rate.toFixed(2)}">
                        <input type="hidden" name="items[${rowCount}][price_type]" value="${priceType}">
                        <input type="hidden" name="items[${rowCount}][unit_price]" value="${rate.toFixed(2)}">
                    </td>
                    <td class="py-2 px-4 border-b">
                        <input type="hidden" name="items[${rowCount}][unit]" value="${unit}">${unit}
                    </td>
                    <td class="py-2 px-4 border-b">
                        <input type="hidden" name="items[${rowCount}][unit_quantity]" value="${unitQuantity.toFixed(2)}">${unitQuantity.toFixed(2)}
                    </td>
                    <td class="py-2 px-4 border-b">
                        <input type="hidden" name="items[${rowCount}][custom_quantity]" value="${customQuantity.toFixed(2)}">${customQuantity.toFixed(2)}
                    </td>
                    <td class="py-2 px-4 border-b">
                        <input type="hidden" name="items[${rowCount}][total_quantity]" value="${totalQuantity.toFixed(2)}">${totalQuantity.toFixed(2)}
                    </td>
                    <td class="py-2 px-4 border-b">
                        <input type="hidden" name="items[${rowCount}][tax_percentage]" value="${taxPercentage.toFixed(2)}">${taxPercentage.toFixed(2)}
                    </td>
                    <td class="py-2 px-4 border-b">
                        <input type="hidden" name="items[${rowCount}][stock]" value="${stock.toFixed(2)}">${stock.toFixed(2)}
                    </td>
                    <td class="py-2 px-4 border-b">
                        <input type="hidden" name="items[${rowCount}][total_amount]" value="${totalAmount.toFixed(2)}">${totalAmount.toFixed(2)}
                        <input type="hidden" name="items[${rowCount}][gross_amount]" value="${grossAmount.toFixed(2)}">
                        <input type="hidden" name="items[${rowCount}][tax_amount]" value="${taxAmount.toFixed(2)}">
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
        document.getElementById('item_id_input').value = '';
        document.getElementById('item_name_input').value = '';
        document.getElementById('rate_input').value = '';
        document.getElementById('unit_quantity_input').value = '';
        document.getElementById('custom_quantity_input').value = '';
        document.getElementById('total_quantity_input').value = '';
        document.getElementById('unit_input').value = '';
        document.getElementById('tax_percentage_input').value = '';
        document.getElementById('stock_input').value = '';

        updateTotals();
    }

    function removeItemRow(button) {
        button.closest('.item-row').remove();
        updateTotals();
    }

    // Close price selector when clicking elsewhere
    $(document).on('click', function() {
        $('.price-type-selector').addClass('hidden');
    });

    // CALCULATION FUNCTIONS
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

        document.getElementById('sale_type').value = paymentOption;

        if (paymentOption === 'Cash') {
            cashInput.value = netTotalAmount.toFixed(2);
            upiInput.value = '';
            cardInput.value = '';
            creditInput.value = '';
        } else if (paymentOption === 'UPI') {
            cashInput.value = '';
            upiInput.value = netTotalAmount.toFixed(2);
            cardInput.value = '';
            creditInput.value = '';
        } else if (paymentOption === 'Card') {
            cashInput.value = '';
            upiInput.value = '';
            cardInput.value = netTotalAmount.toFixed(2);
            creditInput.value = '';
        } else if (paymentOption === 'Credit') {
            cashInput.value = '';
            upiInput.value = '';
            cardInput.value = '';
            creditInput.value = netTotalAmount.toFixed(2);
        } else if (paymentOption === 'Other') {
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
    }

    // Handle Enter key on payment inputs
    function handlePaymentEnter(event, currentId) {
        if (event.keyCode === 13) {
            event.preventDefault();
            const netTotalAmount = parseFloat(document.getElementById('net_total_amount').value) || 0;
            const totalPayment = parseFloat(document.getElementById('total_payment_amount').value) || 0;

            if (Math.abs(netTotalAmount - totalPayment) <= 0.01) {
                return; // Payment is complete, do not move focus
            }

            const paymentInputs = ['cash_amount', 'upi_amount', 'card_amount', 'credit_amount'];
            const currentIndex = paymentInputs.indexOf(currentId);
            let nextIndex = (currentIndex + 1) % paymentInputs.length;

            // Find the next non-filled input
            for (let i = 0; i < paymentInputs.length; i++) {
                const input = document.getElementById(paymentInputs[nextIndex]);
                if (!input.value || parseFloat(input.value) === 0) {
                    input.focus();
                    break;
                }
                nextIndex = (nextIndex + 1) % paymentInputs.length;
            }
        }
    }

    // FORM VALIDATION
    function validateForm() {
        clearErrorMessages();

        const netTotalAmount = parseFloat(document.getElementById('net_total_amount').value) || 0;
        const totalPayment = parseFloat(document.getElementById('total_payment_amount').value) || 0;
        let isValid = true;

        if (Math.abs(netTotalAmount - totalPayment) > 0.01) {
            showErrorMessage('Total payment amount must equal the net total amount.');
            isValid = false;
        }

        return isValid;
    }

    // Clear default values on focus for amount fields
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

    // Bill Search Functionality
    $(document).ready(function() {
        let billCurrentFocus = -1;

        $('#bill_search').on('input', function() {
            let query = $(this).val().trim();
            let suggestions = $('#bill_suggestions');
            suggestions.empty();
            billCurrentFocus = -1;

            if (query.length >= 2) {
                $.ajax({
                    url: '{{ route("sales.search-bills") }}',
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
                        console.error('Error fetching bill data:', error);
                        suggestions.html(
                            `<div class="p-2 text-red-600">Error loading bills</div>`
                        );
                    }
                });
            } else {
                suggestions.addClass('hidden');
            }
        });

        // Handle bill seleção
        $(document).on('click', '.bill-item', function() {
            const billId = $(this).data('id');
            const billNo = $(this).data('bill-no');

            // Set the selected bill in the search field
            $('#bill_search').val(billNo);
            $('#current_bill_id').val(billId);
            $('#bill_suggestions').addClass('hidden');

            // Show clear button
            $('#clear_bill').removeClass('hidden');

            // Load bill details
            loadBillDetails(billId);
        });

        // Clear bill selection
        $('#clear_bill').on('click', function() {
            $('#current_bill_id').val('');
            $('#bill_search').val('');
            $(this).addClass('hidden');
            clearForm();
        });

        // Hide suggestions when clicking elsewhere
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#bill_search, #bill_suggestions').length) {
                $('#bill_suggestions').addClass('hidden');
            }
        });
    });

    function loadBillDetails(billId) {
        // Show loading indicator
        $('#bill_suggestions').html('<div class="p-2 text-gray-600">Loading bill details...</div>');
        $('#bill_suggestions').removeClass('hidden');

        $.ajax({
            url: `/sales/load-bill/${billId}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Full API Response:', response);
                // Fill master details
                const master = response.master;

                $('#sale_date').val(master.sale_date);
                $('#sale_time').val(master.sale_time);
                $('#customer_id').val(master.customer_id);
                $('#customer_name').val(master.customer_name);
                $('#customer_address').val(response.master.customer.address || '');
                $('#payment_option').val(master.sale_type);
                $('#discount').val(master.discount || '');
                $('#narration').val(master.narration || '');

                // Fill payment details
                $('#cash_amount').val(master.cash_amount || '');
                $('#upi_amount').val(master.upi_amount || '');
                $('#card_amount').val(master.card_amount || '');
                $('#credit_amount').val(master.credit_amount || '');

                // Clear existing items
                $('#item-rows').empty();

                // Add items to table
                if (response.items && response.items.length > 0) {
                    response.items.forEach(function(item) {
                        addItemFromBill(item);
                    });
                }

                // Update totals
                updateTotals();

                // Update form action to point to update route
                $('form').attr('action', `/sales/update-bill/${billId}`);
                $('form').attr('method', 'POST');
                // Add method spoofing for PUT
                $('form').find('input[name="_method"]').remove();
                $('form').append('<input type="hidden" name="_method" value="PUT">');

                // Change submit button text
                $('form button[type="submit"]').text('Update Sale');

                $('#bill_suggestions').addClass('hidden');
            },
            error: function(xhr, status, error) {
                console.error('Error loading bill details:', error);
                showErrorMessage('Error loading bill details. Please try again.');
                $('#bill_suggestions').addClass('hidden');
            }
        });
    }

    function addItemFromBill(item) {
        const rowCount = $('#item-rows tr').length;

        const row = `
        <tr class="item-row">
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][item_id]" value="${item.item_id}">${item.item_id}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][item_name]" value="${item.item_name}">${item.item_name}
            </td>
            <td class="py-2 px-4 border-b relative">
                <div class="rate-display">${parseFloat(item.rate).toFixed(2)}</div>
                <input type="hidden" name="items[${rowCount}][rate]" value="${parseFloat(item.rate).toFixed(2)}">
                <input type="hidden" name="items[${rowCount}][price_type]" value="${item.price_type || 'Retail'}">
                <input type="hidden" name="items[${rowCount}][unit_price]" value="${parseFloat(item.unit_price || item.rate).toFixed(2)}">
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][unit]" value="${item.unit}">${item.unit}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][unit_quantity]" value="${parseFloat(item.unit_quantity || 1).toFixed(2)}">${parseFloat(item.unit_quantity || 1).toFixed(2)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][custom_quantity]" value="${parseFloat(item.custom_quantity || item.total_quantity / (item.unit_quantity || 1)).toFixed(2)}">${parseFloat(item.custom_quantity || item.total_quantity / (item.unit_quantity || 1)).toFixed(2)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][total_quantity]" value="${parseFloat(item.total_quantity).toFixed(2)}">${parseFloat(item.total_quantity).toFixed(2)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][tax_percentage]" value="${parseFloat(item.tax_percentage || 0).toFixed(2)}">${parseFloat(item.tax_percentage || 0).toFixed(2)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][stock]" value="${parseFloat(item.stock || 0).toFixed(2)}">${parseFloat(item.stock || 0).toFixed(2)}
            </td>
            <td class="py-2 px-4 border-b">
                <input type="hidden" name="items[${rowCount}][total_amount]" value="${parseFloat(item.total_amount).toFixed(2)}">${parseFloat(item.total_amount).toFixed(2)}
                <input type="hidden" name="items[${rowCount}][gross_amount]" value="${parseFloat(item.gross_amount).toFixed(2)}">
                <input type="hidden" name="items[${rowCount}][tax_amount]" value="${parseFloat(item.tax_amount).toFixed(2)}">
            </td>
            <td class="py-2 px-4 border-b">
                <button type="button" onclick="removeItemRow(this)" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Remove</button>
            </td>
        </tr>`;

        $('#item-rows').append(row);
    }

    function clearForm() {
        // Reset form to create new sale
        $('form').attr('action', '{{ route("sales.store") }}');
        $('form').attr('method', 'POST');
        $('form').find('input[name="_method"]').remove();
        $('form button[type="submit"]').text('Save Sale');

        // Clear all fields except date/time
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

        // Reset totals
        updateTotals();
    }
</script>
</body>
</html>
