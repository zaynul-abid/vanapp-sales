<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Van-Based Sale Report</title>
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
    <h1 class="mt-4 mb-4">Van-Based Sale Report</h1>

    <!-- Filter Form -->
    <div class="filter-container">
        <form method="GET" action="{{ route('van_report.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="filter_type" class="form-label fw-bold">Filter By</label>
                <select name="filter_type" id="filter_type" class="form-select" onchange="updateDatePicker()">
                    <option value="">Select Filter</option>
                    <option value="day" {{ $filterType == 'day' ? 'selected' : '' }}>Day</option>
                    <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>Month</option>
                    <option value="year" {{ $filterType == 'year' ? 'selected' : '' }}>Year</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter_value" class="form-label fw-bold">Select Date</label>
                <input type="text" name="filter_value" id="filter_value" class="form-control"
                       placeholder="Select date" value="{{ $filterValue ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="search_van" class="form-label fw-bold">Search Van</label>
                <input type="text" name="search_van" id="search_van" class="form-control"
                       placeholder="Enter van name" value="{{ $searchVan ?? '' }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">Apply Filter</button>
                <a href="{{ route('van_report.index') }}" class="btn btn-secondary">Clear Filter</a>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Vans:</strong> {{ $totalVans }}</p>
        <p><strong>Total Sales:</strong> {{ $totalSales }}</p>
        <p><strong>Total Net Gross Amount:</strong> {{ number_format($totalNetGross, 2) }}</p>
        <p><strong>Total Net Amount:</strong> {{ number_format($totalNet, 2) }}</p>
    </div>

    <!-- Van Table -->
    <div class="table-container">
        <h3>Van Details</h3>
        @if($vans->isEmpty())
            <p class="text-muted">No vans found for the selected filter.</p>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Van Name</th>
                    <th>Total Sales</th>
                    <th>Net Gross Amount</th>
                    <th>Net Tax Amount</th>
                    <th>Discount</th>
                    <th>Net Total</th>
                    <th>Sale Dates</th>
                </tr>
                </thead>
                <tbody>
                @foreach($vans as $van)
                    <tr>
                        <td>{{ $van->name ?? 'N/A' }}</td>
                        <td>{{ $van->total_sales }}</td>
                        <td>{{ number_format($van->net_gross_amount, 2) }}</td>
                        <td>{{ number_format($van->net_tax_amount, 2) }}</td>
                        <td>{{ number_format($van->discount, 2) }}</td>
                        <td>{{ number_format($van->net_total_amount, 2) }}</td>
                        <td>{{ implode(', ', $van->sale_dates) }}</td>
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

    document.addEventListener('DOMContentLoaded', function() {
        updateDatePicker();
    });
</script>
</body>
</html>
