<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemUnitDetail;
use App\Models\Sale;
use App\Models\SaleMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesController extends Controller
{
    public function index(){
        return view('dashboard.pages.components.sales.index');
    }

    public function create(){

    }
    public function store(Request $request)
    {


        return DB::transaction(function () use ($request) {
            // Generate the Bill No
            $billNo = $this->generateBillNo();
            $vanId = auth()->user()->employee?->van?->id;
            $currentYear = date('Y');
            $financialYear = ($currentYear) . '-' . ($currentYear + 1);

            // Validate stock availability by grouping items by default_item_id
            $itemQuantities = [];
            $itemNames = [];
            $negativeStockItems = [];

            foreach ($request->input('items') as $item) {
                $itemUnitDetailId = $item['item_id'];
                $requestedQuantity = floatval($item['total_quantity']);

                // Get the item unit detail
                $itemUnitDetail = ItemUnitDetail::find($itemUnitDetailId);
                if (!$itemUnitDetail) {
                    return redirect()->back()->with('error', "Invalid item: {$item['item_name']}.");
                }

                // Group quantities by default_item_id
                $defaultItemId = $itemUnitDetail->default_item_id;
                if (!isset($itemQuantities[$defaultItemId])) {
                    $itemQuantities[$defaultItemId] = 0;
                    $itemNames[$defaultItemId] = $item['item_name'];
                }
                $itemQuantities[$defaultItemId] += $requestedQuantity;

                // Validate stock for this default_item_id
                $itemRecord = Item::find($defaultItemId);
                if (!$itemRecord) {
                    return redirect()->back()->with('error', "Item not found for {$itemNames[$defaultItemId]}.");
                }

                // Check if stock would go negative (but don't block it)
                if ($itemRecord->current_stock < $itemQuantities[$defaultItemId]) {
                    $negativeStockItems[] = [
                        'name' => $itemNames[$defaultItemId],
                        'requested' => $itemQuantities[$defaultItemId],
                        'available' => $itemRecord->current_stock
                    ];
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
                'has_negative_stock' => !empty($negativeStockItems),
            ]);

            // Create Sale Items and Update Stock
            foreach ($request->input('items') as $item) {
                $itemUnitDetailId = $item['item_id'];
                $requestedQuantity = floatval($item['total_quantity']);

                // Get the item unit detail
                $itemUnitDetail = ItemUnitDetail::find($itemUnitDetailId);
                $defaultItemId = $itemUnitDetail->default_item_id;

                // Create Sale record
                Sale::create([
                    'sale_master_id' => $saleMaster->id,
                    'bill_no' => $billNo,
                    'sale_date' => $request->input('sale_date'),
                    'sale_time' => $request->input('sale_time'),
                    'customer_id' => $request->input('customer_id'),
                    'item_id' => $itemUnitDetailId,
                    'default_item_id' => $defaultItemId,
                    'item_name' => $item['item_name'],
                    'rate' => $item['rate'],
                    'unit_price' => $item['unit_price'] ?? $item['rate'],
                    'quantity' => $item['total_quantity'],
                    'total_quantity' =>  $item['total_quantity'],
                    'unit_quantity' => $item['unit_quantity'],
                    'custom_quantity' => $item['custom_quantity'],
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
                    'has_negative_stock' => $itemUnitDetail->stock < $requestedQuantity,
                ]);

                // Reduce stock in items table for this item
                $itemRecord = Item::find($defaultItemId);
                $itemRecord->current_stock -= $requestedQuantity;
                $itemRecord->save();

                // Reduce stock in ALL item_unit_details with same default_item_id
                ItemUnitDetail::where('default_item_id', $defaultItemId)
                    ->decrement('stock', $requestedQuantity);
            }

            $message = 'Sale created successfully.';
            if (!empty($negativeStockItems)) {
                $message .= ' Warning: Some items are now in negative stock.';
            }

            return redirect()->route('sales.index')->with('success', $message);
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

    public function searchBills(Request $request)
    {
        try {
            $query = $request->input('query', '');

            // Basic validation
            if (strlen($query) < 2) {
                return response()->json([]);
            }

            $bills = SaleMaster::select([
                'id',
                'bill_no',
                'sale_date',
                'customer_name',
                'net_total_amount'
            ])
                ->where(function($q) use ($query) {
                    $q->where('bill_no', 'like', "%{$query}%")
                        ->orWhere('customer_name', 'like', "%{$query}%");
                })
                ->orderBy('sale_date', 'desc')
                ->limit(20)
                ->get()
                ->toArray();

            return response()->json($bills);

        } catch (\Exception $e) {
            \Log::error('Bill search failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to search bills. Please try again.'
            ], 500);
        }
    }

    public function loadBill($id)
    {
        try {
            $saleMaster = SaleMaster::with(['sales', 'customer'])->findOrFail($id);
            return response()->json([
                'master' => $saleMaster,
                'items' => $saleMaster->sales->map(function($item) {
                    // Fetch stock
                    $stock = \DB::table('item_unit_details')
                        ->where('id', $item->item_id)
                        ->value('stock');
                    // Log the item ID and stock
                    Log::info('Stock fetched', [
                        'item_id' => $item->item_id,
                        'stock' => $stock
                    ]);
                    return [
                        'item_id' => $item->item_id,
                        'item_name' => $item->item_name,
                        'rate' => $item->rate,
                        'unit' => $item->unit,
                        'unit_quantity' => $item->unit_quantity,
                        'custom_quantity' => $item->custom_quantity,
                        'total_quantity' => $item->total_quantity,
                        'tax_percentage' => $item->tax_percentage,
                        'stock' => $stock ?? 0,
                        'total_amount' => $item->total_amount,
                        'gross_amount' => $item->gross_amount,
                        'tax_amount' => $item->tax_amount,
                        'price_type' => $item->price_type,
                        'unit_price' => $item->unit_price,
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            \Log::error('Load bill failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to load bill details.'
            ], 500);
        }
    }

    public function updateBill(Request $request, $id)

    {


        return DB::transaction(function () use ($request, $id) {
            // Find the existing SaleMaster record
            $saleMaster = SaleMaster::findOrFail($id);
            $vanId = auth()->user()->employee?->van?->id;
            $currentYear = date('Y');
            $financialYear = $currentYear . '-' . ($currentYear + 1);

            // Validate stock availability by grouping items by default_item_id
            $itemQuantities = [];
            $itemNames = [];
            $negativeStockItems = [];

            foreach ($request->input('items') as $item) {
                $itemUnitDetailId = $item['item_id'];
                $requestedQuantity = floatval($item['total_quantity']);

                // Get the item unit detail
                $itemUnitDetail = ItemUnitDetail::find($itemUnitDetailId);
                if (!$itemUnitDetail) {
                    return redirect()->back()->with('error', "Invalid item: {$item['item_name']}.");
                }

                // Group quantities by default_item_id
                $defaultItemId = $itemUnitDetail->default_item_id;
                if (!isset($itemQuantities[$defaultItemId])) {
                    $itemQuantities[$defaultItemId] = 0;
                    $itemNames[$defaultItemId] = $item['item_name'];
                }
                $itemQuantities[$defaultItemId] += $requestedQuantity;

                // Validate stock for this default_item_id
                $itemRecord = Item::find($defaultItemId);
                if (!$itemRecord) {
                    return redirect()->back()->with('error', "Item not found for {$itemNames[$defaultItemId]}.");
                }

                // Calculate available stock by adding back the stock from existing sale items
                $existingSales = Sale::where('sale_master_id', $saleMaster->id)
                    ->whereHas('itemUnitDetail', function ($query) use ($defaultItemId) {
                        $query->where('default_item_id', $defaultItemId);
                    })->get();

                $existingQuantity = $existingSales->sum('total_quantity');
                $availableStock = $itemRecord->current_stock + $existingQuantity;

                // Check if stock would go negative (but don't block it)
                if ($availableStock < $itemQuantities[$defaultItemId]) {
                    $negativeStockItems[] = [
                        'name' => $itemNames[$defaultItemId],
                        'requested' => $itemQuantities[$defaultItemId],
                        'available' => $availableStock
                    ];
                }
            }

            // Restore stock for all existing sale items
            $existingSales = Sale::where('sale_master_id', $saleMaster->id)->get();
            foreach ($existingSales as $sale) {
                $itemUnitDetail = ItemUnitDetail::find($sale->item_id);
                if ($itemUnitDetail) {
                    $defaultItemId = $itemUnitDetail->default_item_id;
                    $quantity = floatval($sale->total_quantity);

                    // Restore stock in Item table
                    $itemRecord = Item::find($defaultItemId);
                    if ($itemRecord) {
                        $itemRecord->current_stock += $quantity;
                        $itemRecord->save();
                    }

                    // Restore stock in ALL item_unit_details with same default_item_id
                    ItemUnitDetail::where('default_item_id', $defaultItemId)
                        ->increment('stock', $quantity);
                }
            }

            // Delete existing Sale records
            Sale::where('sale_master_id', $saleMaster->id)->delete();

            // Update SaleMaster
            $saleMaster->update([
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
                'has_negative_stock' => !empty($negativeStockItems),
            ]);

            // Create new Sale Items and Update Stock
            foreach ($request->input('items') as $item) {
                $itemUnitDetailId = $item['item_id'];
                $requestedQuantity = floatval($item['total_quantity']);

                // Get the item unit detail
                $itemUnitDetail = ItemUnitDetail::find($itemUnitDetailId);
                $defaultItemId = $itemUnitDetail->default_item_id;

                // Create Sale record
                Sale::create([
                    'sale_master_id' => $saleMaster->id,
                    'bill_no' => $saleMaster->bill_no,
                    'sale_date' => $request->input('sale_date'),
                    'sale_time' => $request->input('sale_time'),
                    'customer_id' => $request->input('customer_id'),
                    'item_id' => $itemUnitDetailId,
                    'default_item_id' => $defaultItemId,
                    'item_name' => $item['item_name'],
                    'rate' => $item['rate'],
                    'unit_price' => $item['unit_price'] ?? $item['rate'],
                    'quantity' => $item['total_quantity'],
                    'total_quantity' => $item['total_quantity'],
                    'unit_quantity' => $item['unit_quantity'],
                    'custom_quantity' => $item['custom_quantity'],
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
                    'has_negative_stock' => $itemUnitDetail->stock < $requestedQuantity,
                ]);

                // Reduce stock in Item table
                $itemRecord = Item::find($defaultItemId);
                $itemRecord->current_stock -= $requestedQuantity;
                $itemRecord->save();

                // Reduce stock in ALL item_unit_details with same default_item_id
                ItemUnitDetail::where('default_item_id', $defaultItemId)
                    ->decrement('stock', $requestedQuantity);
            }

            $message = 'Sale updated successfully.';
            if (!empty($negativeStockItems)) {
                $message .= ' Warning: Some items are now in negative stock.';
            }

            return redirect()->route('sales.index')->with('success', $message);
        });
    }




}
