<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\SaleMaster;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query for SaleMaster with sales relationship
        $query = SaleMaster::with('sales')->whereNull('deleted_at');

        // Apply date filter if provided
        $filterType = $request->input('filter_type'); // day, month, or year
        $filterValue = $request->input('filter_value'); // e.g., 2025-04-29, 2025-04, or 2025

        if ($filterType && $filterValue) {
            if ($filterType === 'day') {
                $query->whereDate('sale_date', $filterValue);
            } elseif ($filterType === 'month') {
                $query->whereMonth('sale_date', substr($filterValue, 5, 2))
                    ->whereYear('sale_date', substr($filterValue, 0, 4));
            } elseif ($filterType === 'year') {
                $query->whereYear('sale_date', $filterValue);
            }
        }

        // Fetch filtered sale masters with their sales
        $saleMasters = $query->get();

        // Calculate totals using the correct fields
        $totalSales = $saleMasters->count();
        $totalDiscounts = $saleMasters->sum('discount');
        $totalNetGross = $saleMasters->sum('net_gross_amount');
        $totalNetTax = $saleMasters->sum('net_tax_amount');
        $totalNet = $saleMasters->sum('net_total_amount');

        // Group by financial year
        $salesByYear = $saleMasters->groupBy('financial_year')->map(function ($group) {
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
            'filterType',
            'filterValue',
            'totalDiscounts'
        ));
    }

}
