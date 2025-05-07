<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report PDF</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 10mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 10mm;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 16pt;
            margin-bottom: 2mm;
            padding-bottom: 2mm;
            border-bottom: 1px solid #eee;
        }
        .header p {
            margin: 1mm 0;
            font-size: 9pt;
            color: #666;
        }
        .section {
            margin-bottom: 8mm;
            page-break-inside: avoid;
        }
        .section-title {
            color: #2c3e50;
            font-size: 12pt;
            border-bottom: 1px solid #3498db;
            padding-bottom: 2mm;
            margin-bottom: 4mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3mm;
            font-size: 9pt;
            table-layout: fixed;
        }
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: bold;
            padding: 3mm 2mm;
            text-align: left;
            border: 1px solid #ddd;
        }
        td {
            padding: 2mm;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 3mm;
            margin-bottom: 5mm;
        }
        .summary-item {
            padding: 3mm;
            background-color: #f8f9fa;
            border-radius: 2mm;
        }
        .summary-item strong {
            display: block;
            margin-bottom: 1mm;
            color: #2c3e50;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .no-data {
            color: #7f8c8d;
            font-style: italic;
            padding: 3mm;
            text-align: center;
        }
        .footer {
            font-size: 8pt;
            color: #7f8c8d;
            text-align: center;
            margin-top: 5mm;
            border-top: 1px solid #eee;
            padding-top: 2mm;
        }
        /* Column widths for detailed table */
        .col-billno { width: 10%; }
        .col-date { width: 8%; }
        .col-customer { width: 15%; }
        .col-type { width: 8%; }
        .col-amount { width: 10%; }
        .col-tax { width: 10%; }
        .col-discount { width: 8%; }
        .col-employee { width: 12%; }
        .col-van { width: 10%; }
        .col-total { width: 9%; }
    </style>
</head>
<body>
<div class="header">
    <h1>Sales Report</h1>
    <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
    @if($fromDate && $toDate)
        <p>Date Range: {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</p>
    @endif
</div>

<!-- Summary Statistics -->
<div class="section">
    <h2 class="section-title">Summary Statistics</h2>
    <div class="summary-grid">
        <div class="summary-item">
            <strong>Total Sales</strong>
            <span>{{ $totalSales }}</span>
        </div>
        <div class="summary-item">
            <strong>Total Net Gross Amount</strong>
            <span>{{ number_format($totalNetGross, 2) }}</span>
        </div>
        <div class="summary-item">
            <strong>Total Net Tax Amount</strong>
            <span>{{ number_format($totalNetTax, 2) }}</span>
        </div>
        <div class="summary-item">
            <strong>Total Discount Amount</strong>
            <span>{{ number_format($totalDiscounts, 2) }}</span>
        </div>
        <div class="summary-item">
            <strong>Total Net Amount</strong>
            <span>{{ number_format($totalNet, 2) }}</span>
        </div>
    </div>
</div>

<!-- Sales by Financial Year -->
<div class="section">
    <h2 class="section-title">Sales by Financial Year</h2>
    <table>
        <thead>
        <tr>
            <th class="text-center">Financial Year</th>
            <th class="text-center">Count</th>
            <th class="text-right">Net Gross</th>
            <th class="text-right">Discount</th>
            <th class="text-right">Tax</th>
            <th class="text-right">Net Total</th>
        </tr>
        </thead>
        <tbody>
        @forelse($salesByYear as $year => $data)
            <tr>
                <td class="text-center">{{ $year }}</td>
                <td class="text-center">{{ $data['count'] }}</td>
                <td class="text-right">{{ number_format($data['net_gross_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($data['total_discount'], 2) }}</td>
                <td class="text-right">{{ number_format($data['net_tax_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($data['net_total_amount'], 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="no-data">No financial year data available</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- Detailed Sales Table -->
<div class="section">
    <h2 class="section-title">Detailed Sales Records</h2>
    @if($saleMasters->isEmpty())
        <p class="no-data">No sales found for the selected filter.</p>
    @else
        <table>
            <thead>
            <tr>
                <th class="col-billno">Bill No</th>
                <th class="col-date">Date</th>
                <th class="col-customer">Customer</th>
                <th class="col-type">Type</th>
                <th class="col-amount text-right">Gross Amount</th>
                <th class="col-tax text-right">Tax</th>
                <th class="col-discount text-right">Discount</th>
                <th class="col-employee">Employee</th>
                <th class="col-van">Van</th>
                <th class="col-total text-right">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($saleMasters as $saleMaster)
                <tr>
                    <td class="col-billno">{{ $saleMaster->bill_no }}</td>
                    <td class="col-date">{{ \Carbon\Carbon::parse($saleMaster->sale_date)->format('d/m/Y') }}</td>
                    <td class="col-customer">{{ $saleMaster->customer->name ?? 'N/A' }}</td>
                    <td class="col-type">{{ $saleMaster->sale_type }}</td>
                    <td class="col-amount text-right">{{ number_format($saleMaster->net_gross_amount, 2) }}</td>
                    <td class="col-tax text-right">{{ number_format($saleMaster->net_tax_amount, 2) }}</td>
                    <td class="col-discount text-right">{{ number_format($saleMaster->discount, 2) }}</td>
                    <td class="col-employee">{{ $saleMaster->user->name ?? 'N/A' }}</td>
                    <td class="col-van">{{ $saleMaster->van->name ?? 'N/A' }}</td>
                    <td class="col-total text-right">{{ number_format($saleMaster->net_total_amount, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="footer">
    Page 1 of 1 • Confidential • {{ config('app.name') }}
</div>
</body>
</html>
