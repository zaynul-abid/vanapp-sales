<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        .filter-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .filter-container .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s;
        }
        .filter-container .btn-primary:hover {
            background-color: #0056b3;
        }
        .filter-container .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .table-container { margin-top: 20px; }
        .summary {
            margin-bottom: 20px;
            padding: 15px;
            background: #e9ecef;
            border-radius: 8px;
        }
        .summary h3, .table-container h3 {
            color: #343a40;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="mt-4 mb-4">Sales Report</h1>


    <!-- Filter Form -->
    <div class="filter-container">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="filter_type" class="form-label fw-bold">Filter By</label>
                <select name="filter_type" id="filter_type" class="form-select" onchange="updateDatePicker()">
                    <option value="">Select Filter</option>
                    <option value="day" {{ $filterType == 'day' ? 'selected' : '' }}>Day</option>
                    <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>Month</option>
                    <option value="year" {{ $filterType == 'year' ? 'selected' : '' }}>Year</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filter_value" class="form-label fw-bold">Select Date</label>
                <input type="text" name="filter_value" id="filter_value" class="form-control"
                       placeholder="Select date" value="{{ $filterValue ?? '' }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary me-2">Apply Filter</button>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">Clear Filter</a>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Sales:</strong> {{ $totalSales }}</p>
        <p><strong>Total Net Gross Amount:</strong> {{ number_format($totalNetGross, 2) }}</p>
        <p><strong>Total Net Tax Amount:</strong> {{ number_format($totalNetTax, 2) }}</p>
        <p><strong>Total Discount Amount:</strong> {{ number_format($totalDiscounts, 2) }}</p>
        <p><strong>Total Net Amount:</strong> {{ number_format($totalNet, 2) }}</p>
    </div>

    <!-- Sales by Financial Year -->
    <div class="summary">
        <h3>Sales by Financial Year</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Financial Year</th>
                <th>Count</th>
                <th>Net Gross Amount</th>
                <th>Discount Amount</th>
                <th>Net Tax Amount</th>
                <th>Net Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($salesByYear as $year => $data)
                <tr>
                    <td>{{ $year }}</td>
                    <td>{{ $data['count'] }}</td>
                    <td>{{ number_format($data['net_gross_amount'], 2) }}</td>
                    <td>{{ number_format($data['total_discount'], 2) }}</td>
                    <td>{{ number_format($data['net_tax_amount'], 2) }}</td>
                    <td>{{ number_format($data['net_total_amount'], 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Detailed Sales Table -->
    <div class="table-container">
        <h3>Sale Masters and Details</h3>
        @if($saleMasters->isEmpty())
            <p class="text-muted">No sales found for the selected filter.</p>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Bill No</th>
                    <th>Sale Date</th>
                    <th>Customer </th>
                    <th>Sale Type</th>
                    <th>Net Gross Amount</th>
                    <th>Net Tax Amount</th>
                    <th>Discount</th>
                    <th>Employee Name</th>
                    <th>Van Details</th>
                    <th>Net Total</th>
                    <th>Details</th>
                </tr>
                </thead>
                <tbody>
                @foreach($saleMasters as $saleMaster)
                    <tr>
                        <td>{{ $saleMaster->bill_no }}</td>
                        <td>{{ $saleMaster->sale_date }}</td>
                        <td>{{ $saleMaster->customer->name ?? 'N/A' }}</td>
                        <td>{{ $saleMaster->sale_type }}</td>
                        <td>{{ number_format($saleMaster->net_gross_amount, 2) }}</td>
                        <td>{{ number_format($saleMaster->net_tax_amount, 2) }}</td>
                        <td>{{ number_format($saleMaster->discount, 2) }}</td>
                        <td>{{ $saleMaster->user->name ?? 'N/A' }}</td>
                        <td>{{ $saleMaster->van->name ?? 'N/A' }}</td>
                        <td>{{ $saleMaster->customer->name ?? 'N/A' }}</td>
                        <td>{{ number_format($saleMaster->net_total_amount, 2) }}</td>
                        <td>
                            @if($saleMaster->sales->isEmpty())
                                <span class="text-muted">No items</span>
                            @else
                                <ul class="list-unstyled">


                                @foreach($saleMaster->sales as $sale)
                                        <li>
                                            Item Name: {{ $sale->item_name ?? 'N/A' }} |
                                            Amount: {{ number_format($sale->total_amount ?? 0, 2) }}
                                            <!-- Add more sale columns as needed -->
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script>
    function updateDatePicker() {
        const filterType = document.getElementById('filter_type').value;
        const filterValueInput = document.getElementById('filter_value');
        let dateFormat = "Y-m-d";
        let enableTime = false;

        if (filterType === 'day') {
            dateFormat = "Y-m-d";
        } else if (filterType === 'month') {
            dateFormat = "Y-m";
        } else if (filterType === 'year') {
            dateFormat = "Y";
        } else {
            filterValueInput.value = '';
            return;
        }

        flatpickr(filterValueInput, {
            dateFormat: dateFormat,
            enableTime: enableTime,
            maxDate: "today",
        });
    }

    // Initialize date picker on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateDatePicker();
    });
</script>
</body>
</html>
