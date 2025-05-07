<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Customer Report PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h3 {
            color: #343a40;
            text-align: center;
        }
        .summary {
            margin-bottom: 20px;
            padding: 15px;
            background: #e9ecef;
            border-radius: 8px;
        }
        .summary p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .filter-info {
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Customer Report</h1>

    @if($startDate && $endDate)
        <div class="filter-info">
            <p><strong>Date Range:</strong> {{ $startDate }} to {{ $endDate }}</p>
        </div>
    @endif

    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Customers:</strong> {{ $totalCustomers }}</p>
        <p><strong>Total Net Gross Amount:</strong> {{ number_format($totalNetGross, 2) }}</p>
        <p><strong>Total Net Tax Amount:</strong> {{ number_format($totalNetTax, 2) }}</p>
        <p><strong>Total Discount Amount:</strong> {{ number_format($totalDiscounts, 2) }}</p>
        <p><strong>Total Net Amount:</strong> {{ number_format($totalNet, 2) }}</p>
    </div>

    <h3>Customer Details</h3>
    @if($customers->isEmpty())
        <p>No customers found for the selected filter.</p>
    @else
        <table>
            <thead>
            <tr>
                <th>Customer Name</th>
                <th>Total Purchases</th>
                <th>Net Gross Amount</th>
                <th>Net Tax Amount</th>
                <th>Discount</th>
                <th>Net Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->name ?? 'N/A' }}</td>
                    <td>{{ $customer->total_purchases }}</td>
                    <td>{{ number_format($customer->net_gross_amount, 2) }}</td>
                    <td>{{ number_format($customer->net_tax_amount, 2) }}</td>
                    <td>{{ number_format($customer->discount, 2) }}</td>
                    <td>{{ number_format($customer->net_total_amount, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
</body>
</html>
