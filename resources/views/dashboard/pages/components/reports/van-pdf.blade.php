<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Van-Based Sale Report PDF</title>
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
        .container {
            width: 90%;
            margin: 0 auto;
            text-align: center;
        }
        .header {
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
        /* Column widths for van table */
        .col-van-name { width: 20%; }
        .col-total-sales { width: 15%; }
        .col-net-gross { width: 15%; }
        .col-net-tax { width: 15%; }
        .col-discount { width: 15%; }
        .col-net-total { width: 20%; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Van-Based Sale Report</h1>
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
        @if($startDate && $endDate)
            <p>Date Range: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        @endif
    </div>

    <!-- Summary Statistics -->
    <div class="section">
        <h2 class="section-title">Summary Statistics</h2>
        <div class="summary-grid">
            <div class="summary-item">
                <strong>Total Vans</strong>
                <span>{{ $totalVans }}</span>
            </div>
            <div class="summary-item">
                <strong>Total Sales</strong>
                <span>{{ $totalSales }}</span>
            </div>
            <div class="summary-item">
                <strong>Total Net Gross Amount</strong>
                <span>{{ number_format($totalNetGross, 2) }}</span>
            </div>
            <div class="summary-item">
                <strong>Total Net Amount</strong>
                <span>{{ number_format($totalNet, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Van Details Table -->
    <div class="section">
        <h2 class="section-title">Van Details</h2>
        @if($vans->isEmpty())
            <p class="no-data">No vans found for the selected filter.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th class="col-van-name">Van Name</th>
                    <th class="col-total-sales text-center">Total Sales</th>
                    <th class="col-net-gross text-right">Net Gross Amount</th>
                    <th class="col-net-tax text-right">Net Tax Amount</th>
                    <th class="col-discount text-right">Discount</th>
                    <th class="col-net-total text-right">Net Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($vans as $van)
                    <tr>
                        <td class="col-van-name">{{ $van->name ?? 'N/A' }}</td>
                        <td class="col-total-sales text-center">{{ $van->total_sales }}</td>
                        <td class="col-net-gross text-right">{{ number_format($van->net_gross_amount, 2) }}</td>
                        <td class="col-net-tax text-right">{{ number_format($van->net_tax_amount, 2) }}</td>
                        <td class="col-discount text-right">{{ number_format($van->discount, 2) }}</td>
                        <td class="col-net-total text-right">{{ number_format($van->net_total_amount, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="footer">
        Page 1 of 1 • Confidential • {{ config('app.name') }}
    </div>
</div>
</body>
</html>
