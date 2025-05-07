<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Report PDF</title>
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
        .top-items {
            padding: 3mm;
            background-color: #d4edda;
            border-radius: 2mm;
            margin-bottom: 5mm;
        }
        .top-items table {
            background-color: #fff;
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
        /* Column widths for item table */
        .col-item-name { width: 30%; }
        .col-quantity-sold { width: 20%; }
        .col-unit-price { width: 20%; }
        .col-total-amount { width: 30%; }
        /* Column widths for top items table */
        .col-top-item-name { width: 40%; }
        .col-top-quantity-sold { width: 30%; }
        .col-top-total-amount { width: 30%; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Stock Report</h1>
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
        @if($startDate && $endDate)
            <p>Date Range: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        @endif
    </div>

    <!-- Top Selling Items -->
    @if($topItems->isNotEmpty())
        <div class="section">
            <h2 class="section-title">Top Selling Items</h2>
            <div class="top-items">
                <table>
                    <thead>
                    <tr>
                        <th class="col-top-item-name">Item Name</th>
                        <th class="col-top-quantity-sold text-center">Quantity Sold</th>
                        <th class="col-top-total-amount text-right">Total Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($topItems as $item)
                        <tr>
                            <td class="col-top-item-name">{{ $item->item_name ?? 'N/A' }}</td>
                            <td class="col-top-quantity-sold text-center">{{ $item->quantity_sold }}</td>
                            <td class="col-top-total-amount text-right">{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Summary Statistics -->
    <div class="section">
        <h2 class="section-title">Summary Statistics</h2>
        <div class="summary-grid">
            <div class="summary-item">
                <strong>Total Items Sold</strong>
                <span>{{ $totalItemsSold }}</span>
            </div>
            <div class="summary-item">
                <strong>Total Quantity Sold</strong>
                <span>{{ $totalQuantitySold }}</span>
            </div>
            <div class="summary-item">
                <strong>Total Net Amount</strong>
                <span>{{ number_format($totalNet, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Stock Details Table -->
    <div class="section">
        <h2 class="section-title">Stock Details</h2>
        @if($items->isEmpty())
            <p class="no-data">No items found for the selected filter.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th class="col-item-name">Item Name</th>
                    <th class="col-quantity-sold text-center">Quantity Sold</th>
                    <th class="col-unit-price text-right">Unit Price</th>
                    <th class="col-total-amount text-right">Total Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td class="col-item-name">{{ $item->item_name ?? 'N/A' }}</td>
                        <td class="col-quantity-sold text-center">{{ $item->quantity_sold }}</td>
                        <td class="col-unit-price text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="col-total-amount text-right">{{ number_format($item->total_amount, 2) }}</td>
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
