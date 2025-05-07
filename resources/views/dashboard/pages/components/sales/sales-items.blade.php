<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Details - {{ $saleMaster->bill_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Inter', sans-serif;
        }
        .container {
            max-width: 1200px;
            padding: 2rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #007bff, #00b4d8);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }
        .list-group-item {
            border: none;
            padding: 0.75rem 1.25rem;
        }
        .table-container {
            margin-top: 2rem;
        }
        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }
        .table th {
            background-color: #e9ecef;
            color: #343a40;
        }
        .btn-outline-primary {
            transition: all 0.3s ease;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: white;
        }
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Sales</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bill No: {{ $saleMaster->bill_no }}</li>
        </ol>
    </nav>

    <!-- Sale Information Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="mb-0">Sale Information</h3>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 fw-bold">Bill No:</dt>
                        <dd class="col-sm-7">{{ $saleMaster->bill_no }}</dd>
                        <dt class="col-sm-5 fw-bold">Sale Date:</dt>
                        <dd class="col-sm-7">{{ $saleMaster->sale_date }}</dd>
                        <dt class="col-sm-5 fw-bold">Sale Time:</dt>
                        <dd class="col-sm-7">{{ $saleMaster->sale_time }}</dd>
                        <dt class="col-sm-5 fw-bold">Customer:</dt>
                        <dd class="col-sm-7">{{ $saleMaster->customer_name ?? 'N/A' }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 fw-bold">Sale Type:</dt>
                        <dd class="col-sm-7">{{ $saleMaster->sale_type ?? 'N/A' }}</dd>
                        <dt class="col-sm-5 fw-bold">Gross Amount:</dt>
                        <dd class="col-sm-7">{{ number_format($saleMaster->gross_amount ?? 0, 2) }}</dd>
                        <dt class="col-sm-5 fw-bold">Tax Amount:</dt>
                        <dd class="col-sm-7">{{ number_format($saleMaster->tax_amount ?? 0, 2) }}</dd>
                        <dt class="col-sm-5 fw-bold">Discount:</dt>
                        <dd class="col-sm-7">{{ number_format($saleMaster->discount ?? 0, 2) }}</dd>
                        <dt class="col-sm-5 fw-bold">Net Total:</dt>
                        <dd class="col-sm-7">{{ number_format($saleMaster->net_total_amount ?? 0, 2) }}</dd>
                    </dl>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 fw-bold">Van:</dt>
                        <dd class="col-sm-7">{{ $saleMaster->van->name ?? 'N/A' }}</dd>
                        <dt class="col-sm-5 fw-bold">Employee:</dt>
                        <dd class="col-sm-7">{{ $saleMaster->user->name ?? 'N/A' }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 fw-bold">Narration:</dt>
                        <dd class="col-sm-7">{{ $saleMaster->narration ?? 'N/A' }}</dd>
                        <dt class="col-sm-5 fw-bold">Financial Year:</dt>
                        <dd class="col-sm-7">{{ $saleMaster->financial_year ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Sold Card -->
    <div class="card table-container">
        <div class="card-header">
            <h3 class="mb-0">Items Sold</h3>
        </div>
        <div class="card-body p-4">
            @if($saleMaster->sales->isEmpty())
                <p class="text-muted">No items found for this sale.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Van</th>
                            <th>Employee</th>
                            <th>Tax Amount</th>
                            <th>Total Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($saleMaster->sales as $sale)
                            <tr>
                                <td>{{ $sale->item_name ?? 'N/A' }}</td>
                                <td>{{ $sale->quantity ?? 0 }}</td>
                                <td>${{ number_format($sale->unit_price ?? 0, 2) }}</td>
                                <td>{{ $saleMaster->van->name ?? 'N/A' }}</td>
                                <td>{{ $saleMaster->user->name ?? 'N/A' }}</td>
                                <td>${{ number_format($sale->tax_amount ?? 0, 2) }}</td>
                                <td>${{ number_format($sale->total_amount ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('sales.index') }}" class="btn btn-outline-primary">Back to Reports</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
