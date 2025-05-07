<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Sales Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { margin-top: 20px; }
        .table-container { margin-top: 20px; }
        .table-container h3 {
            color: #343a40;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="{{ route('employee_report.index') }}" class="btn btn-primary mt-3">Back</a>
    <h1 class="mt-4 mb-4">Sales Details for {{ $employeeName }}</h1>

    <div class="table-container">
        <h3>Sale Details</h3>
        @if($sales->isEmpty())
            <p class="text-muted">No sales found for this employee.</p>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Bill Number</th>
                    <th>Sale Date</th>
                    <th>Van Name</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale->bill_number }}</td>
                        <td>{{ $sale->sale_date }}</td>
                        <td>{{ $sale->van_name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
