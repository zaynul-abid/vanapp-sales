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


    public function customerReport(Request $request){
        {
            $filterType = $request->input('filter_type');
            $filterValue = $request->input('filter_value');
            $searchCustomer = $request->input('search_customer');

            $query = SaleMaster::with('customer', 'sales')
                ->whereHas('customer', function ($q) use ($searchCustomer) {
                    if ($searchCustomer) {
                        $q->where('name', 'like', "%{$searchCustomer}%");
                    }
                });

            // Apply date filter
            if ($filterType && $filterValue) {
                $date = Carbon::parse($filterValue);
                if ($filterType === 'day') {
                    $query->whereDate('sale_date', $date);
                } elseif ($filterType === 'month') {
                    $query->whereMonth('sale_date', $date->month)
                        ->whereYear('sale_date', $date->year);
                } elseif ($filterType === 'year') {
                    $query->whereYear('sale_date', $date->year);
                }
            }

            $saleMasters = $query->get();
            $customers = $saleMasters->groupBy('customer_id')->map(function ($group) {
                $totalPurchases = $group->count();
                $netGrossAmount = $group->sum('net_gross_amount');
                $netTaxAmount = $group->sum('net_tax_amount');
                $discount = $group->sum('discount');
                $netTotalAmount = $group->sum('net_total_amount');
                $saleDates = $group->pluck('sale_date')->unique()->toArray();

                return (object) [
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
                'filterType',
                'filterValue',
                'searchCustomer'
            ));
        }
    }


    public function employeeReport(Request $request)
    {
        $filterType = $request->input('filter_type');
        $filterValue = $request->input('filter_value');
        $searchEmployee = $request->input('search_employee');

        $query = SaleMaster::with('user', 'sales')
            ->whereHas('user', function ($q) use ($searchEmployee) {
                if ($searchEmployee) {
                    $q->where('name', 'like', "%{$searchEmployee}%");
                }
            });

        if ($filterType && $filterValue) {
            $date = Carbon::parse($filterValue);
            if ($filterType === 'day') {
                $query->whereDate('sale_date', $date);
            } elseif ($filterType === 'month') {
                $query->whereMonth('sale_date', $date->month)
                    ->whereYear('sale_date', $date->year);
            } elseif ($filterType === 'year') {
                $query->whereYear('sale_date', $date->year);
            }
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
            'filterType',
            'filterValue',
            'searchEmployee'
        ));
    }

    public function vanReport(Request $request)
    {
        $filterType = $request->input('filter_type');
        $filterValue = $request->input('filter_value');
        $searchVan = $request->input('search_van');

        $query = SaleMaster::with('van', 'sales')
            ->whereHas('van', function ($q) use ($searchVan) {
                if ($searchVan) {
                    $q->where('name', 'like', "%{$searchVan}%");
                }
            });

        if ($filterType && $filterValue) {
            $date = Carbon::parse($filterValue);
            if ($filterType === 'day') {
                $query->whereDate('sale_date', $date);
            } elseif ($filterType === 'month') {
                $query->whereMonth('sale_date', $date->month)
                    ->whereYear('sale_date', $date->year);
            } elseif ($filterType === 'year') {
                $query->whereYear('sale_date', $date->year);
            }
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
            'filterType',
            'filterValue',
            'searchVan'
        ));
    }


    public function stockReport(Request $request)
    {
        $filterType = $request->input('filter_type');
        $filterValue = $request->input('filter_value');
        $searchItem = $request->input('search_item');

        $query = Sale::with('saleMaster', 'item')
            ->whereHas('item', function ($q) use ($searchItem) {
                if ($searchItem) {
                    $q->where('item_name', 'like', "%{$searchItem}%");
                }
            });

        if ($filterType && $filterValue) {
            $date = Carbon::parse($filterValue);
            if ($filterType === 'day') {
                $query->whereHas('saleMaster', function ($q) use ($date) {
                    $q->whereDate('sale_date', $date);
                });
            } elseif ($filterType === 'month') {
                $query->whereHas('saleMaster', function ($q) use ($date) {
                    $q->whereMonth('sale_date', $date->month)
                        ->whereYear('sale_date', $date->year);
                });
            } elseif ($filterType === 'year') {
                $query->whereHas('saleMaster', function ($q) use ($date) {
                    $q->whereYear('sale_date', $date->year);
                });
            }
        }

        $sales = $query->get();
        $items = $sales->groupBy('item_id')->map(function ($group) {
            $quantitySold = $group->sum('quantity');
            $unitPrice = $group->first()->unit_price ?? 0;
            $totalAmount = $group->sum('total_amount');
            $saleDates = $group->pluck('saleMaster.sale_date')->unique()->toArray();

            return (object) [
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

        return view('dashboard.pages.components.reports.stock-report', compact(
            'items',
            'totalItemsSold',
            'totalQuantitySold',
            'totalNet',
            'filterType',
            'filterValue',
            'searchItem'
        ));
    }
}
