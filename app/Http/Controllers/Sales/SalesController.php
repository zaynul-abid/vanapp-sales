<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(){
        return view('dashboard.pages.components.sales.index');
    }

    public function create(){

    }
    public function store(Request $request)
    {

        DB::beginTransaction();

        try {
            // 1. Generate the Bill No
            $billNo = $this->generateBillNo();
            $vanId = auth()->user()->van ? auth()->user()->van->id : null;

            // 2. Create SaleMaster
            $saleMaster = SaleMaster::create([
                'bill_no' => $billNo,
                'sale_date' => $request->input('sale_date'),
                'sale_time' => $request->input('sale_time'),
                'customer_id' => $request->input('customer_id'),
                'customer_name' => $request->input('customer_name'),
                'sale_type' => $request->input('sale_type'),
                'gross_amount' => $request->input('gross_amount'),
                'tax_amount' => $request->input('tax_amount'),
                'total_amount' => $request->input('total_amount'),
                'discount' => $request->input('discount'),
                'net_gross_amount' => $request->input('net_gross_amount'),
                'net_tax_amount' => $request->input('net_tax_amount'),
                'net_total_amount' => $request->input('net_total_amount'),
                'narration' => $request->input('narration'),
                'cash_amount' => $request->input('cash_amount'),
                'credit_amount' => $request->input('credit_amount'),
                'upi_amount' => $request->input('upi_amount'),
                'financial_year' => $request->input('financial_year'),
                'van_id' => $vanId,
                'user_id' => $request->input('user_id'),
            ]);

            // 3. Create Sale Items
            foreach ($request->input('items') as $item) {
                Sale::create([
                    'sales_master_id' => $saleMaster->id,
                    'bill_no' => $billNo,
                    'sale_date' => $request->input('sale_date'),
                    'sale_time' => $request->input('sale_time'),
                    'customer_id' => $request->input('customer_id'),
                    'item_id' => $item['item_id'],
                    'item_name' => $item['item_name'],
                    'rate' => $item['rate'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'gross_amount' => $item['gross_amount'],
                    'tax_amount' => $item['tax_amount'],
                    'total_amount' => $item['total_amount'],
                    'narration' => $request->input('narration'),
                    'financial_year' => $request->input('financial_year'),
                    'van_id' => $vanId,
                    'user_id' => $request->input('user_id'),
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Sale stored successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate the next bill number
     *
     * @return string
     */
    private function generateBillNo()
    {
        // Get the last SaleMaster including soft-deleted ones
        $lastSaleMaster = SaleMaster::withTrashed()
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSaleMaster) {
            // Remove BILL- prefix and convert to integer
            $lastNumber = intval(str_replace('BILL-', '', $lastSaleMaster->bill_no));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1; // first bill
        }

        // Format: BILL-0001
        return 'BILL-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
