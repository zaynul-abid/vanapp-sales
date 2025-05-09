<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Report</title>
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
        .top-items {
            margin-bottom: 20px;
            padding: 15px;
            background: #d4edda;
            border-radius: 8px;
        }
        .top-items h3 {
            color: #343a40;
            border-bottom: 2px solid #28a745;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    @if(auth()->user()->isEmployee())
        <a href="{{ route('employee.dashboard') }}" class="btn btn-primary mt-3">Back</a>
    @else
        <a href="{{ route('reports.index') }}" class="btn btn-primary mt-3">Back</a>
    @endif

    <h1 class="mt-4 mb-4">Stock Report</h1>

    <!-- Filter Form -->
    <div class="filter-container">
        <form method="GET" action="{{ route('stock_report.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="start_date" class="form-label fw-bold">From Date</label>
                <input type="text" name="start_date" id="start_date" class="form-control"
                       placeholder="Select start date" value="{{ $startDate ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label fw-bold">To Date</label>
                <input type="text" name="end_date" id="end_date" class="form-control"
                       placeholder="Select end date" value="{{ $endDate ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="search_item" class="form-label fw-bold">Search Item</label>
                <input type="text" name="search_item" id="search_item" class="form-control"
                       placeholder="Enter item name" value="{{ $searchItem ?? '' }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">Apply Filter</button>
                <a href="{{ route('stock_report.index') }}" class="btn btn-secondary">Clear Filter</a>
            </div>
            <div class="col-12">
                <a href="{{ route('stock_report.pdf') }}" class="btn btn-success">Export PDF</a>
            </div>
        </form>
    </div>

    <!-- Top Selling Items -->
    @if($topItems->isNotEmpty())
        <div class="top-items">
            <h3>Top Selling Items</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity Sold</th>
                    <th>Total Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($topItems as $item)
                    <tr>
                        <td>{{ $item->item_name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity_sold }}</td>
                        <td>{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Summary Statistics -->
    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Items Sold:</strong> {{ $totalItemsSold }}</p>
        <p><strong>Total Quantity Sold:</strong> {{ $totalQuantitySold }}</p>
        <p><strong>Total Net Amount:</strong> {{ number_format($totalNet, 2) }}</p>
    </div>

    <!-- Stock Table -->
    <div class="table-container">
        <h3>Stock Details</h3>
        @if($items->isEmpty())
            <p class="text-muted">No items found for the selected filter.</p>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity Sold</th>
                    <th>Unit Price</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->item_name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity_sold }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format($item->total_amount, 2) }}</td>
                        <td>
                            <a href="{{ route('stock_report.details', ['item_id' => $item->item_id]) }}"
                               class="btn btn-info btn-sm">Details</a>
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
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#start_date", {
            dateFormat: "Y-m-d",
            maxDate: "today"
        });
        flatpickr("#end_date", {
            dateFormat: "Y-m-d",
            maxDate: "today"
        });
    });
</script>
</body>
</html>
