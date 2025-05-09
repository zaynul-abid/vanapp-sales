<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #1a1a1a;
            color: #f8f9fa;
        }
        .container {
            padding: 40px 0;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        .card-hover {
            transition: transform 0.3s ease;
        }
        header {
            text-align: center;
            margin-bottom: 40px;
        }
        /* Modal Styles */
        .reports-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .reports-modal.show {
            display: flex;
            opacity: 1;
        }
        .modal-content {
            background: #1a1a1a;
            border-radius: 12px;
            padding: 25px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            transform: scale(0.7);
            transition: transform 0.3s ease;
            border: 1px solid #17a2b8;
        }
        .reports-modal.show .modal-content {
            transform: scale(1);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .modal-title {
            color: #f8f9fa;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #f8f9fa;
            cursor: pointer;
        }
        .report-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .report-card {
            background: #1a1a1a;
            border: 1px solid #17a2b8;
            border-radius: 8px;
            text-align: center;
            padding: 15px;
            transition: transform 0.3s ease;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .report-card i {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }
        .report-card h5 {
            font-size: 1rem;
            color: #f8f9fa;
            margin-bottom: 10px;
        }
        .report-card a {
            display: inline-block;
            background: #17a2b8;
            color: #fff;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .report-card a:hover {
            background: #138496;
        }
        @media (max-width: 768px) {
            .report-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 576px) {
            .modal-content {
                padding: 15px;
            }
            .report-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            .report-card {
                padding: 10px;
            }
            .report-card i {
                font-size: 1.5rem;
            }
            .report-card h5 {
                font-size: 0.9rem;
            }
            .report-card a {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1 class="display-4 fw-bold">VANAPP-SALES</h1>
        <p class="lead text-muted">Welcome back, User</p>
    </header>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <a href="/employee/create-item" class="text-decoration-none">
                <div class="card bg-dark border-primary card-hover h-100">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-plus-circle-fill text-primary mb-3" style="font-size: 2.5rem;"></i>
                        <h3 class="card-title h4">Item Creation</h3>
                        <p class="card-text text-muted">Add new products to inventory</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="/sales/create" class="text-decoration-none">
                <div class="card bg-dark border-success card-hover h-100">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-cart-check-fill text-success mb-3" style="font-size: 2.5rem;"></i>
                        <h3 class="card-title h4">Sales</h3>
                        <p class="card-text text-muted">View and process customer sales</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="#" class="text-decoration-none" onclick="showReportsModal()">
                <div class="card bg-dark border-info card-hover h-100">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-graph-up-arrow text-info mb-3" style="font-size: 2.5rem;"></i>
                        <h3 class="card-title h4">Reports</h3>
                        <p class="card-text text-muted">Generate business reports</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <form method="POST" action="/logout" class="h-100">
                <button type="submit" class="w-100 h-100 bg-transparent border-0 p-0">
                    <div class="card bg-dark border-danger card-hover h-100">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-box-arrow-right text-danger mb-3" style="font-size: 2.5rem;"></i>
                            <h3 class="card-title h4">Logout</h3>
                            <p class="card-text text-muted">Sign out of your account</p>
                        </div>
                    </div>
                </button>
            </form>
        </div>
    </div>

    <!-- Reports Modal -->
    <div class="reports-modal" id="reportsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reports</h5>
                <button class="close-btn" onclick="hideReportsModal()">×</button>
            </div>
            <div class="report-grid">
                <div class="report-card card-hover">
                    <i class="bi bi-person-fill text-primary"></i>
                    <h5>Customer</h5>
                    <a href="{{route('customer_report.index')}}">View</a>
                </div>
                <div class="report-card card-hover">
                    <i class="bi bi-truck text-success"></i>
                    <h5>Van</h5>
                    <a href="{{route('van_report.index')}}">View</a>
                </div>
                <div class="report-card card-hover">
                    <i class="bi bi-people-fill text-warning"></i>
                    <h5>Employee</h5>
                    <a href="{{route('employee_report.index')}}">View</a>
                </div>
                <div class="report-card card-hover">
                    <i class="bi bi-boxes text-danger"></i>
                    <h5>Stock</h5>
                    <a href="{{route('stock_report.index')}}">View</a>
                </div>
                <div class="report-card card-hover">
                    <i class="bi bi-currency-dollar text-info"></i>
                    <h5>Sale</h5>
                    <a href="{{route('sale_report.index')}}">View</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showReportsModal() {
        const modal = document.getElementById('reportsModal');
        modal.classList.add('show');
    }

    function hideReportsModal() {
        const modal = document.getElementById('reportsModal');
        modal.classList.remove('show');
    }

    document.getElementById('reportsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideReportsModal();
        }
    });
</script>
</body>
</html>
