<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Details - {{ $saleMaster->bill_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { padding: 20px; }
        .card { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card-header { background-color: #007bff; color: white; }
        .table-container { margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <h1 class="mb-4">Sale Details - Bill No: {{ $saleMaster->bill_no }}</h1>

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Sale Information</h3>
        </div>
        <div class="card-body">
            <p><strong>Sale Date:</strong> {{ $saleMaster->sale_date }}</p>
            <p><strong>Customer:</strong> {{ $saleMaster->customer->name ?? 'N/A' }}</p>
            <p><strong>Sale Type:</strong> {{ $saleMaster->sale_type }}</p>
            <p><strong>Net Gross Amount:</strong> {{ number_format($saleMaster->net_gross_amount, 2) }}</p>
            <p><strong>Net Tax Amount:</strong> {{ number_format($saleMaster->net_tax_amount, 2) }}</p>
            <p><strong>Discount:</strong> {{ number_format($saleMaster->discount, 2) }}</p>
            <p><strong>Employee Name:</strong> {{ $saleMaster->user->name ?? 'N/A' }}</p>
            <p><strong>Van Details:</strong> {{ $saleMaster->van->name ?? 'N/A' }}</p>
            <p><strong>Net Total:</strong> {{ number_format($saleMaster->net_total_amount, 2) }}</p>
        </div>
    </div>

    <div class="table-container">
        <h3>Items Sold</h3>
        @if($saleMaster->sales->isEmpty())
            <p class="text-muted">No items found for this sale.</p>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Tax Amount</th>
                    <th>Total Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($saleMaster->sales as $sale)
                    <tr>
                        <td>{{ $sale->item_name ?? 'N/A' }}</td>
                        <td>{{ $sale->quantity ?? 0 }}</td>
                        <td>{{ number_format($sale->unit_price ?? 0, 2) }}</td>
                        <td>{{ number_format($sale->tax_amount ?? 0, 2) }}</td>
                        <td>{{ number_format($sale->total_amount ?? 0, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">Back to Reports</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
