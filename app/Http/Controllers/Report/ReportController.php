<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleMaster;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = SaleMaster::with(['sales', 'customer', 'user', 'van'])->whereNull('deleted_at');

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        if ($fromDate && $toDate) {
            $query->whereBetween('sale_date', [$fromDate, $toDate]);
        }

        // Single search for both customer name and bill number
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('bill_no', 'LIKE', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $saleMasters = $query->paginate(10);

        $totalSales = $saleMasters->total();
        $totalDiscounts = $saleMasters->sum('discount');
        $totalNetGross = $saleMasters->sum('net_gross_amount');
        $totalNetTax = $saleMasters->sum('net_tax_amount');
        $totalNet = $saleMasters->sum('net_total_amount');

        $salesByYearQuery = SaleMaster::whereNull('deleted_at');
        if ($fromDate && $toDate) {
            $salesByYearQuery->whereBetween('sale_date', [$fromDate, $toDate]);
        }
        $salesByYear = $salesByYearQuery->get()->groupBy('financial_year')->map(function ($group) {
            return [
                'count' => $group->count(),
                'net_gross_amount' => $group->sum('net_gross_amount'),
                'net_tax_amount' => $group->sum('net_tax_amount'),
                'total_discount' => $group->sum('discount'),
                'net_total_amount' => $group->sum('net_total_amount'),
            ];
        })->toArray();

        return view('dashboard.pages.components.reports.index', compact(
            'saleMasters',
            'totalSales',
            'totalNetGross',
            'totalNetTax',
            'totalNet',
            'salesByYear',
            'totalDiscounts',
            'fromDate',
            'toDate',
            'search' // Pass the single search term to the view
        ));
    }
    public function saleReport(Request $request)
    {
        $query = SaleMaster::with(['sales', 'customer', 'user', 'van'])->whereNull('deleted_at');

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        if ($fromDate && $toDate) {
            $query->whereBetween('sale_date', [$fromDate, $toDate]);
        }

        // Single search for both customer name and bill number
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('bill_no', 'LIKE', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $saleMasters = $query->paginate(10);

        $totalSales = $saleMasters->total();
        $totalDiscounts = $saleMasters->sum('discount');
        $totalNetGross = $saleMasters->sum('net_gross_amount');
        $totalNetTax = $saleMasters->sum('net_tax_amount');
        $totalNet = $saleMasters->sum('net_total_amount');

        $salesByYearQuery = SaleMaster::whereNull('deleted_at');
        if ($fromDate && $toDate) {
            $salesByYearQuery->whereBetween('sale_date', [$fromDate, $toDate]);
        }
        $salesByYear = $salesByYearQuery->get()->groupBy('financial_year')->map(function ($group) {
            return [
                'count' => $group->count(),
                'net_gross_amount' => $group->sum('net_gross_amount'),
                'net_tax_amount' => $group->sum('net_tax_amount'),
                'total_discount' => $group->sum('discount'),
                'net_total_amount' => $group->sum('net_total_amount'),
            ];
        })->toArray();

        return view('dashboard.pages.components.reports.index', compact(
            'saleMasters',
            'totalSales',
            'totalNetGross',
            'totalNetTax',
            'totalNet',
            'salesByYear',
            'totalDiscounts',
            'fromDate',
            'toDate',
            'search' // Pass the single search term to the view
        ));
    }

    public function showSaleItemDetails($saleMasterId)
    {
        $saleMaster = SaleMaster::with(['sales', 'customer', 'user', 'van'])->findOrFail($saleMasterId);
        return view('dashboard.pages.components.reports.sales-item', compact('saleMaster'));
    }

    public function salesReportPdf(Request $request)
    {
        $query = SaleMaster::with(['sales', 'customer', 'user', 'van'])->whereNull('deleted_at');

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        if ($fromDate && $toDate) {
            $query->whereBetween('sale_date', [$fromDate, $toDate]);
        }

        $search = $request->input('search'); // Updated to use single search field
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('bill_no', 'LIKE', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $saleMasters = $query->get();

        $totalSales = $saleMasters->count();
        $totalDiscounts = $saleMasters->sum('discount');
        $totalNetGross = $saleMasters->sum('net_gross_amount');
        $totalNetTax = $saleMasters->sum('net_tax_amount');
        $totalNet = $saleMasters->sum('net_total_amount');

        $salesByYear = $saleMasters->groupBy('financial_year')->map(function ($group) {
            return [
                'count' => $group->count(),
                'net_gross_amount' => $group->sum('net_gross_amount'),
                'net_tax_amount' => $group->sum('net_tax_amount'),
                'total_discount' => $group->sum('discount'),
                'net_total_amount' => $group->sum('net_total_amount'),
            ];
        })->toArray();

        // Load the view for PDF and set A4 size
        $pdf = Pdf::loadView('dashboard.pages.components.reports.sale-pdf', compact(
            'saleMasters',
            'totalSales',
            'totalNetGross',
            'totalNetTax',
            'totalNet',
            'salesByYear',
            'totalDiscounts',
            'fromDate',
            'toDate'
        ))->setPaper('a4', 'portrait'); // Set A4 size and orientation

        return $pdf->download('sales-report.pdf');
    }


    public function customerReport(Request $request)
    {
        {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $searchCustomer = $request->input('search_customer');

            $query = SaleMaster::with('customer', 'sales')
                ->whereHas('customer', function ($q) use ($searchCustomer) {
                    if ($searchCustomer) {
                        $q->where('name', 'like', "%{$searchCustomer}%");
                    }
                });

            // Apply date range filter
            if ($startDate && $endDate) {
                $start = Carbon::parse($startDate);
                $end = Carbon::parse($endDate);
                $query->whereBetween('sale_date', [$start, $end]);
            }

            $saleMasters = $query->get();
            $customers = $saleMasters->groupBy('customer_id')->map(function ($group) {
                $totalPurchases = $group->count();
                $netGrossAmount = $group->sum('net_gross_amount');
                $netTaxAmount = $group->sum('net_tax_amount');
                $discount = $group->sum('discount');
                $netTotalAmount = $group->sum('net_total_amount');
                $saleDates = $group->pluck('sale_date')->unique()->toArray();

                return (object)[
                    'customer_id' => $group->first()->customer_id,
                    'name' => $group->first()->customer->name ?? 'N/A',
                    'total_purchases' => $totalPurchases,
                    'net_gross_amount' => $netGrossAmount,
                    'net_tax_amount' => $netTaxAmount,
                    'discount' => $discount,
                    'net_total_amount' => $netTotalAmount,
                    'sale_dates' => $saleDates,
                ];
            })->values();

            $totalCustomers = $customers->count();
            $totalNetGross = $customers->sum('net_gross_amount');
            $totalNetTax = $customers->sum('net_tax_amount');
            $totalDiscounts = $customers->sum('discount');
            $totalNet = $customers->sum('net_total_amount');

            return view('dashboard.pages.components.reports.customer_report', compact(
                'customers',
                'totalCustomers',
                'totalNetGross',
                'totalNetTax',
                'totalDiscounts',
                'totalNet',
                'startDate',
                'endDate',
                'searchCustomer'
            ));
        }
    }

    public function showCustomerDetails(Request $request, $customer_id){
        $sales = SaleMaster::with('customer')
            ->where('customer_id', $customer_id)
            ->get()
            ->map(function ($sale) {
                return (object) [
                    'bill_number' => $sale->bill_no ?? 'N/A',
                    'sale_date' => $sale->sale_date,
                ];
            });

        $customerName = $sales->first()->customer->name ?? 'N/A';

        return view('dashboard.pages.components.reports.customer-details', compact(
            'sales',
            'customerName',
            'customer_id'
        ));
    }

    public function customerReportPdf(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchCustomer = $request->input('search_customer');

        $query = SaleMaster::with('customer', 'sales')
            ->whereHas('customer', function ($q) use ($searchCustomer) {
                if ($searchCustomer) {
                    $q->where('name', 'like', "%{$searchCustomer}%");
                }
            });

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $query->whereBetween('sale_date', [$start, $end]);
        }

        $saleMasters = $query->get();
        $customers = $saleMasters->groupBy('customer_id')->map(function ($group) {
            $totalPurchases = $group->count();
            $netGrossAmount = $group->sum('net_gross_amount');
            $netTaxAmount = $group->sum('net_tax_amount');
            $discount = $group->sum('discount');
            $netTotalAmount = $group->sum('net_total_amount');

            return (object) [
                'name' => $group->first()->customer->name ?? 'N/A',
                'total_purchases' => $totalPurchases,
                'net_gross_amount' => $netGrossAmount,
                'net_tax_amount' => $netTaxAmount,
                'discount' => $discount,
                'net_total_amount' => $netTotalAmount,
            ];
        })->values();

        $totalCustomers = $customers->count();
        $totalNetGross = $customers->sum('net_gross_amount');
        $totalNetTax = $customers->sum('net_tax_amount');
        $totalDiscounts = $customers->sum('discount');
        $totalNet = $customers->sum('net_total_amount');

        $pdf = Pdf::loadView('dashboard.pages.components.reports.customer_pdf', compact(
            'customers',
            'totalCustomers',
            'totalNetGross',
            'totalNetTax',
            'totalDiscounts',
            'totalNet',
            'startDate',
            'endDate'
        ));

        return $pdf->download('customer_report.pdf');
    }



    public function employeeReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchEmployee = $request->input('search_employee');

        $query = SaleMaster::with('user', 'sales')
            ->whereHas('user', function ($q) use ($searchEmployee) {
                if ($searchEmployee) {
                    $q->where('name', 'like', "%{$searchEmployee}%");
                }
            });

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $query->whereBetween('sale_date', [$start, $end]);
        }

        $saleMasters = $query->get();
        $employees = $saleMasters->groupBy('user_id')->map(function ($group) {
            $totalSales = $group->count();
            $netGrossAmount = $group->sum('net_gross_amount');
            $netTaxAmount = $group->sum('net_tax_amount');
            $discount = $group->sum('discount');
            $netTotalAmount = $group->sum('net_total_amount');
            $saleDates = $group->pluck('sale_date')->unique()->toArray();

            return (object) [
                'user_id' => $group->first()->user_id,
                'name' => $group->first()->user->name ?? 'N/A',
                'total_sales' => $totalSales,
                'net_gross_amount' => $netGrossAmount,
                'net_tax_amount' => $netTaxAmount,
                'discount' => $discount,
                'net_total_amount' => $netTotalAmount,
                'sale_dates' => $saleDates,
            ];
        })->values();

        $totalEmployees = $employees->count();
        $totalSales = $employees->sum('total_sales');
        $totalNetGross = $employees->sum('net_gross_amount');
        $totalNet = $employees->sum('net_total_amount');

        return view('dashboard.pages.components.reports.employee-report', compact(
            'employees',
            'totalEmployees',
            'totalSales',
            'totalNetGross',
            'totalNet',
            'startDate',
            'endDate',
            'searchEmployee'
        ));
    }

    public function ShowEmployeeDetails(Request $request, $employee_id)
    {
        $sales = SaleMaster::with('user', 'van')
            ->where('user_id', $employee_id)
            ->get()
            ->map(function ($sale) {
                return (object) [
                    'bill_number' => $sale->bill_no ?? 'N/A',
                    'sale_date' => $sale->sale_date,
                    'van_name' => $sale->van->name ?? 'N/A',
                ];
            });

        $employeeName = $sales->first()->user->name ?? 'N/A';

        return view('dashboard.pages.components.reports.employee-details', compact(
            'sales',
            'employeeName',
            'employee_id'
        ));
    }


    public function EmployeeReportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchEmployee = $request->input('search_employee');

        $query = SaleMaster::with('user', 'sales')
            ->whereHas('user', function ($q) use ($searchEmployee) {
                if ($searchEmployee) {
                    $q->where('name', 'like', "%{$searchEmployee}%");
                }
            });

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $query->whereBetween('sale_date', [$start, $end]);
        }

        $saleMasters = $query->get();
        $employees = $saleMasters->groupBy('user_id')->map(function ($group) {
            $totalSales = $group->count();
            $netGrossAmount = $group->sum('net_gross_amount');
            $netTaxAmount = $group->sum('net_tax_amount');
            $discount = $group->sum('discount');
            $netTotalAmount = $group->sum('net_total_amount');

            return (object) [
                'name' => $group->first()->user->name ?? 'N/A',
                'total_sales' => $totalSales,
                'net_gross_amount' => $netGrossAmount,
                'net_tax_amount' => $netTaxAmount,
                'discount' => $discount,
                'net_total_amount' => $netTotalAmount,
            ];
        })->values();

        $totalEmployees = $employees->count();
        $totalSales = $employees->sum('total_sales');
        $totalNetGross = $employees->sum('net_gross_amount');
        $totalNet = $employees->sum('net_total_amount');

        $pdf = Pdf::loadView('dashboard.pages.components.reports.employee-pdf', compact(
            'employees',
            'totalEmployees',
            'totalSales',
            'totalNetGross',
            'totalNet',
            'startDate',
            'endDate'
        ));

        return $pdf->download('employee_report.pdf');
    }

    public function vanReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchVan = $request->input('search_van');

        $query = SaleMaster::with('van', 'sales')
            ->whereHas('van', function ($q) use ($searchVan) {
                if ($searchVan) {
                    $q->where('name', 'like', "%{$searchVan}%");
                }
            });

        // Apply date range filter
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $query->whereBetween('sale_date', [$start, $end]);
        }

        $saleMasters = $query->get();
        $vans = $saleMasters->groupBy('van_id')->map(function ($group) {
            $totalSales = $group->count();
            $netGrossAmount = $group->sum('net_gross_amount');
            $netTaxAmount = $group->sum('net_tax_amount');
            $discount = $group->sum('discount');
            $netTotalAmount = $group->sum('net_total_amount');
            $saleDates = $group->pluck('sale_date')->unique()->toArray();

            return (object) [
                'van_id' => $group->first()->van_id,
                'name' => $group->first()->van->name ?? 'N/A',
                'total_sales' => $totalSales,
                'net_gross_amount' => $netGrossAmount,
                'net_tax_amount' => $netTaxAmount,
                'discount' => $discount,
                'net_total_amount' => $netTotalAmount,
                'sale_dates' => $saleDates,
            ];
        })->values();

        $totalVans = $vans->count();
        $totalSales = $vans->sum('total_sales');
        $totalNetGross = $vans->sum('net_gross_amount');
        $totalNet = $vans->sum('net_total_amount');

        return view('dashboard.pages.components.reports.van-report', compact(
            'vans',
            'totalVans',
            'totalSales',
            'totalNetGross',
            'totalNet',
            'startDate',
            'endDate',
            'searchVan'
        ));
    }

    public function ShowVanDetails(Request $request, $van_id)
    {
    $sales = SaleMaster::with('van', 'user')
        ->where('van_id', $van_id)
        ->get()
        ->map(function ($sale) {
            return (object) [
                'bill_number' => $sale->bill_no ?? 'N/A',
                'sale_date' => $sale->sale_date,
                'employee_name' => $sale->user->name ?? 'N/A',
            ];
        });

    $vanName = $sales->first()->van->name ?? 'N/A';

    return view('dashboard.pages.components.reports.van-details', compact(
        'sales',
        'vanName',
        'van_id'
    ));
}

    public function vanReportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchVan = $request->input('search_van');

        $query = SaleMaster::with('van', 'sales')
            ->whereHas('van', function ($q) use ($searchVan) {
                if ($searchVan) {
                    $q->where('name', 'like', "%{$searchVan}%");
                }
            });

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $query->whereBetween('sale_date', [$start, $end]);
        }

        $saleMasters = $query->get();
        $vans = $saleMasters->groupBy('van_id')->map(function ($group) {
            $totalSales = $group->count();
            $netGrossAmount = $group->sum('net_gross_amount');
            $netTaxAmount = $group->sum('net_tax_amount');
            $discount = $group->sum('discount');
            $netTotalAmount = $group->sum('net_total_amount');

            return (object) [
                'name' => $group->first()->van->name ?? 'N/A',
                'total_sales' => $totalSales,
                'net_gross_amount' => $netGrossAmount,
                'net_tax_amount' => $netTaxAmount,
                'discount' => $discount,
                'net_total_amount' => $netTotalAmount,
            ];
        })->values();

        $totalVans = $vans->count();
        $totalSales = $vans->sum('total_sales');
        $totalNetGross = $vans->sum('net_gross_amount');
        $totalNet = $vans->sum('net_total_amount');

        $pdf = Pdf::loadView('dashboard.pages.components.reports.van-pdf', compact(
            'vans',
            'totalVans',
            'totalSales',
            'totalNetGross',
            'totalNet',
            'startDate',
            'endDate'
        ));

        return $pdf->download('van_report.pdf');
    }




    public function stockReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchItem = $request->input('search_item');

        $query = Sale::with('saleMaster', 'item')
            ->whereHas('item', function ($q) use ($searchItem) {
                if ($searchItem) {
                    $q->where('item_name', 'like', "%{$searchItem}%");
                }
            });

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $query->whereHas('saleMaster', function ($q) use ($start, $end) {
                $q->whereBetween('sale_date', [$start, $end]);
            });
        }

        $sales = $query->get();
        $items = $sales->groupBy('item_id')->map(function ($group) {
            $quantitySold = $group->sum('quantity');
            $unitPrice = $group->first()->unit_price ?? 0;
            $totalAmount = $group->sum('total_amount');
            $saleDates = $group->pluck('saleMaster.sale_date')->unique()->toArray();

            return (object) [
                'item_id' => $group->first()->item_id,
                'item_name' => $group->first()->item->name ?? 'N/A',
                'quantity_sold' => $quantitySold,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'sale_dates' => $saleDates,
            ];
        })->values();

        $totalItemsSold = $items->count();
        $totalQuantitySold = $items->sum('quantity_sold');
        $totalNet = $items->sum('total_amount');

        // Get top 5 selling items by quantity sold
        $topItems = $items->sortByDesc('quantity_sold')->take(10);

        return view('dashboard.pages.components.reports.stock-report', compact(
            'items',
            'totalItemsSold',
            'totalQuantitySold',
            'totalNet',
            'startDate',
            'endDate',
            'searchItem',
            'topItems'
        ));
    }

        public function ShowStockDetails(Request $request, $item_id)
    {
        $sales = Sale::with('saleMaster.van', 'saleMaster.user')
            ->where('item_id', $item_id)
            ->get()
            ->map(function ($sale) {
                return (object) [
                    'bill_number' => $sale->saleMaster->bill_no ?? 'N/A',
                    'sale_date' => $sale->saleMaster->sale_date,
                    'van_name' => $sale->saleMaster->van->name ?? 'N/A',
                    'employee_name' => $sale->saleMaster->user->name ?? 'N/A',
                ];
            });

        $itemName = $sales->first()->item->item_name ?? 'N/A';

        return view('dashboard.pages.components.reports.stock-details', compact(
            'sales',
            'itemName',
            'item_id'
        ));
    }

        public function stockReportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchItem = $request->input('search_item');

        $query = Sale::with('saleMaster', 'item')
            ->whereHas('item', function ($q) use ($searchItem) {
                if ($searchItem) {
                    $q->where('item_name', 'like', "%{$searchItem}%");
                }
            });

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $query->whereHas('saleMaster', function ($q) use ($start, $end) {
                $q->whereBetween('sale_date', [$start, $end]);
            });
        }

        $sales = $query->get();
        $items = $sales->groupBy('item_id')->map(function ($group) {
            $quantitySold = $group->sum('quantity');
            $unitPrice = $group->first()->unit_price ?? 0;
            $totalAmount = $group->sum('total_amount');

            return (object) [
                'item_name' => $group->first()->item->name ?? 'N/A',
                'quantity_sold' => $quantitySold,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
            ];
        })->values();

        $totalItemsSold = $items->count();
        $totalQuantitySold = $items->sum('quantity_sold');
        $totalNet = $items->sum('total_amount');

        // Get top 5 selling items by quantity sold
        $topItems = $items->sortByDesc('quantity_sold')->take(5);

        $pdf = Pdf::loadView('dashboard.pages.components.reports.stock-pdf', compact(
            'items',
            'totalItemsSold',
            'totalQuantitySold',
            'totalNet',
            'startDate',
            'endDate',
            'topItems'
        ));

        return $pdf->download('stock_report.pdf');
    }
}
