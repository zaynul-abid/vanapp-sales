<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report PDF</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            font-size: 12pt;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #343a40;
            font-size: 18pt;
            margin-bottom: 10mm;
        }
        h3 {
            color: #343a40;
            border-bottom: 2px solid #007bff;
            padding-bottom: 3mm;
            font-size: 14pt;
            margin-top: 0;
            margin-bottom: 5mm;
        }
        .summary, .table-container {
            margin-bottom: 10mm;
            page-break-inside: avoid; /* Prevent breaking inside sections */
        }
        .summary p {
            margin: 2mm 0;
            font-size: 12pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5mm;
            font-size: 10pt;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 3mm;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        /* Ensure long tables break properly across pages */
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        /* Add page break before the detailed sales table if needed */
        .table-container {
            page-break-before: auto;
        }
    </style>
</head>
<body>
<h1>Sales Report</h1>

@if($fromDate && $toDate)
    <p><strong>Date Range:</strong> {{ $fromDate }} to {{ $toDate }}</p>
@endif

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
    <table>
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
        <p>No sales found for the selected filter.</p>
    @else
        <table>
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
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
</body>
</html>
