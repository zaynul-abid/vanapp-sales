<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemUnitDetail;
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
        // Start a database transaction
        return DB::transaction(function () use ($request) {
            // Generate the Bill No
            $billNo = $this->generateBillNo();
            $vanId = auth()->user()->employee?->van?->id;
            $currentYear = date('Y');
            $financialYear = ($currentYear) . '-' . ($currentYear + 1);

            // Validate stock availability
            foreach ($request->input('items') as $item) {
                $itemId = $item['item_id'];
                $requestedQuantity = $item['total_quantity'];

                // Check stock in items table
                $itemRecord = Item::find($itemId);
                if (!$itemRecord || $itemRecord->current_stock < $requestedQuantity) {
                    $availableStock = $itemRecord ? $itemRecord->current_stock : 0;
                    return redirect()->back()->with('error', "Insufficient stock for item {$item['item_name']}. Requested: {$requestedQuantity}, Available: {$availableStock}.");
                }

                // Check stock in item_unit_details table
                $itemUnitDetail = ItemUnitDetail::where('default_item_id', $itemId)->first();
                if (!$itemUnitDetail || $itemUnitDetail->stock < $requestedQuantity) {
                    $availableStock = $itemUnitDetail ? $itemUnitDetail->stock : 0;
                    return redirect()->back()->with('error', "Insufficient stock for item {$item['item_name']} in unit details. Requested: {$requestedQuantity}, Available: {$availableStock}.");
                }
            }

            // Create SaleMaster
            $saleMaster = SaleMaster::create([
                'bill_no' => $billNo,
                'sale_date' => $request->input('sale_date'),
                'sale_time' => $request->input('sale_time'),
                'customer_id' => $request->input('customer_id'),
                'customer_name' => $request->input('customer_name'),
                'customer_address' => $request->input('customer_address'),
                'sale_type' => $request->input('sale_type'),
                'gross_amount' => $request->input('gross_amount'),
                'tax_amount' => $request->input('tax_amount'),
                'total_amount' => $request->input('total_amount'),
                'discount' => $request->input('discount') ?? 0,
                'net_gross_amount' => $request->input('net_gross_amount'),
                'net_tax_amount' => $request->input('net_tax_amount'),
                'net_total_amount' => $request->input('net_total_amount'),
                'narration' => $request->input('narration'),
                'cash_amount' => $request->input('cash_amount') ?? 0,
                'credit_amount' => $request->input('credit_amount') ?? 0,
                'upi_amount' => $request->input('upi_amount') ?? 0,
                'card_amount' => $request->input('card_amount') ?? 0,
                'financial_year' => $financialYear,
                'van_id' => $vanId,
                'user_id' => auth()->id(),
            ]);

            // Create Sale Items and Update Stock
            foreach ($request->input('items') as $item) {
                $itemId = $item['item_id'];
                $requestedQuantity = $item['total_quantity'];

                // Create Sale record
                Sale::create([
                    'sale_master_id' => $saleMaster->id,
                    'bill_no' => $billNo,
                    'sale_date' => $request->input('sale_date'),
                    'sale_time' => $request->input('sale_time'),
                    'customer_id' => $request->input('customer_id'),
                    'item_id' => $itemId,
                    'item_name' => $item['item_name'],
                    'rate' => $item['rate'],
                    'unit_price' => $item['unit_price'] ?? $item['rate'],
                    'quantity' => $requestedQuantity,
                    'unit' => $item['unit'],
                    'gross_amount' => $item['gross_amount'],
                    'tax_amount' => $item['tax_amount'],
                    'total_amount' => $item['total_amount'],
                    'tax_percentage' => $item['tax_percentage'] ?? 0,
                    'price_type' => $item['price_type'] ?? 'Retail',
                    'narration' => $request->input('narration'),
                    'financial_year' => $financialYear,
                    'van_id' => $vanId,
                    'user_id' => auth()->id(),
                ]);

                // Reduce stock in items table
                $itemRecord = Item::find($itemId);
                $itemRecord->current_stock -= $requestedQuantity;
                $itemRecord->save();

                // Reduce stock in item_unit_details table
                $itemUnitDetail = ItemUnitDetail::where('default_item_id', $itemId)->first();
                $itemUnitDetail->stock -= $requestedQuantity;
                $itemUnitDetail->save();
            }

            return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
        });

    }
    /**
     * Generate the next bill number
     */
    private function generateBillNo()
    {
        $lastSaleMaster = SaleMaster::withTrashed()
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSaleMaster) {
            // Get the numeric part
            $lastNumber = intval(str_replace('BILL-', '', $lastSaleMaster->bill_no));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Format with leading zeros
        return 'BILL-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

}
