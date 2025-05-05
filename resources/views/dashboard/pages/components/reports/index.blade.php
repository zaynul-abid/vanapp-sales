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
            <div class="col-md-3">
                <label for="from_date" class="form-label fw-bold">From Date</label>
                <input type="text" name="from_date" id="from_date" class="form-control"
                       placeholder="Select start date" value="{{ $fromDate ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="to_date" class="form-label fw-bold">To Date</label>
                <input type="text" name="to_date" id="to_date" class="form-control"
                       placeholder="Select end date" value="{{ $toDate ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="search" class="form-label fw-bold">Search</label>
                <input type="text" name="search" id="search" class="form-control"
                       placeholder="Search by customer or bill no" value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">Apply Filter</button>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
        <!-- Export to PDF Button -->
        <form method="GET" action="{{ route('sales-report.pdf') }}" class="mt-3">
            <input type="hidden" name="from_date" value="{{ $fromDate ?? '' }}">
            <input type="hidden" name="to_date" value="{{ $toDate ?? '' }}">
            <input type="hidden" name="search" value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-success">Export to PDF</button>
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
                    <th>Customer</th>
                    <th>Sale Type</th>
                    <th>Net Gross Amount</th>
                    <th>Net Tax Amount</th>
                    <th>Discount</th>
                    <th>Employee Name</th>
                    <th>Van Details</th>
                    <th>Net Total</th>
                    <th>Actions</th>
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
                        <td>{{ number_format($saleMaster->net_total_amount, 2) }}</td>
                        <td>
                            <a href="{{ route('showSale.item', $saleMaster->id) }}" class="btn btn-sm btn-info" target="_blank">Show Details</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $saleMasters->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#from_date", {
            dateFormat: "Y-m-d",
            maxDate: "today",
        });

        flatpickr("#to_date", {
            dateFormat: "Y-m-d",
            maxDate: "today",
        });
    });
</script>
</body>
</html>
