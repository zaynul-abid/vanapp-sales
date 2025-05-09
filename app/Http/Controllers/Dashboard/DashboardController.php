<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleMaster;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function superadminIndex()
    {
        // Fetch sales data
        $saleMasters = SaleMaster::whereNull('deleted_at')->get();

        $totalSales = $saleMasters->count();
        $totalNet = $saleMasters->sum('net_total_amount');

        // Group sales by financial year
        $salesByYear = $saleMasters->groupBy('financial_year')->map(function ($group) {
            return [
                'count' => $group->count(),
                'net_total_amount' => $group->sum('net_total_amount'),
            ];
        })->toArray();

        $years = array_keys($salesByYear);
        $salesData = array_column($salesByYear, 'net_total_amount');

        // Fetch top selling items
        $sales = Sale::with('item')->get();
        $topItems = $sales->groupBy('item_id')->map(function ($group) {
            $quantitySold = $group->sum('quantity');
            $totalAmount = $group->sum('total_amount');
            return (object) [
                'item_name' => $group->first()->item->name ?? 'N/A',
                'quantity_sold' => $quantitySold,
                'total_amount' => $totalAmount,
            ];
        })->sortByDesc('quantity_sold')->take(10)->values();

        $topItemNames = $topItems->pluck('item_name')->toArray();
        $topItemQuantities = $topItems->pluck('quantity_sold')->toArray();

        return view('dashboard.pages.superadmin.dashboard', compact(
            'totalSales',
            'totalNet',
            'years',
            'salesData',
            'topItems',
            'topItemNames',
            'topItemQuantities'
        ));
    }
    public function adminIndex(){
        return view('dashboard.pages.admin.dashboard');
    }


    public function employeeIndex(){

        return view('dashboard.pages.employee.dashboard');
    }
}
