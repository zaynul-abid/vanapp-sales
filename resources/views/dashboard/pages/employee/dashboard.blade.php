<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        .card-hover {
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="bg-dark text-light">
<div class="container py-5">
    <header class="mb-5 text-center">
        <h1 class="display-4 fw-bold">VANAPP-SALES</h1>
        <p class="lead text-muted">Welcome back, {{ auth()->user()->name }}</p>
    </header>

    <div class="row g-4">
        <!-- Item Creation Card -->
        <div class="col-md-6 col-lg-3">
            <a href="{{route('items.index')}}" class="text-decoration-none">
                <div class="card bg-dark border-primary card-hover h-100">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-plus-circle-fill text-primary mb-3" style="font-size: 2.5rem;"></i>
                        <h3 class="card-title h4">Item Creation</h3>
                        <p class="card-text text-muted">Add new products to inventory</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Sales Card -->
        <div class="col-md-6 col-lg-3">
            <a href="{{route('sales.index')}}" class="text-decoration-none">
                <div class="card bg-dark border-success card-hover h-100">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-cart-check-fill text-success mb-3" style="font-size: 2.5rem;"></i>
                        <h3 class="card-title h4">Sales</h3>
                        <p class="card-text text-muted">View and process customer sales</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Reports Card -->
        <div class="col-md-6 col-lg-3">
            <a href="" class="text-decoration-none">
                <div class="card bg-dark border-info card-hover h-100">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-graph-up-arrow text-info mb-3" style="font-size: 2.5rem;"></i>
                        <h3 class="card-title h4">Reports</h3>
                        <p class="card-text text-muted">Generate business reports</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Logout Card -->
        <div class="col-md-6 col-lg-3">
            <form method="POST" action="{{ route('logout') }}" class="h-100">
                @csrf
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
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
