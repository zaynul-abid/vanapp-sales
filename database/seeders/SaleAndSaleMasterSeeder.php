<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Item;
use App\Models\ItemUnitDetail;
use App\Models\Sale;
use App\Models\SaleMaster;
use App\Models\Van;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleAndSaleMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $count Number of SaleMaster records to create
     * @return void
     */
    public function run($count = 500)
    {
        $customers = Customer::all();
        $vans = Van::all();
        $items = Item::all();
        $itemUnitDetails = ItemUnitDetail::all()->groupBy('default_item_id');

        if ($customers->isEmpty() || $vans->isEmpty() || $items->isEmpty() || $itemUnitDetails->isEmpty()) {
            throw new \Exception('Please seed Customers, Vans, Items, and ItemUnitDetails before running this seeder.');
        }

        // Generate the specified number of SaleMaster records
        for ($i = 0; $i < $count; $i++) {
            DB::transaction(function () use ($customers, $vans, $items, $itemUnitDetails, $i) {
                // Generate random date and time within the past year
                $saleDate = Carbon::now()->subDays(rand(0, 365))->format('Y-m-d');
                $saleTime = Carbon::createFromTime(rand(0, 23), rand(0, 59), rand(0, 59))->format('H:i:s');
                $currentYear = Carbon::parse($saleDate)->year;
                $financialYear = "$currentYear-" . ($currentYear + 1);
                $van = $vans->random();
                $employee = Employee::find($van->employee_id);

                if (!$employee) {
                    throw new \Exception("No employee found for van ID {$van->id}.");
                }

                $customer = $customers->random();
                $saleType = ['Cash', 'Credit', 'UPI', 'Card'][rand(0, 3)];

                // Financial calculations
                $grossAmount = rand(1000, 10000);
                $discount = rand(0, 10) / 100 * $grossAmount;
                $taxAmount = rand(5, 15) / 100 * $grossAmount;
                $netGrossAmount = $grossAmount - $discount;
                $netTaxAmount = $taxAmount;
                $netTotalAmount = $netGrossAmount + $netTaxAmount;

                // Payment breakdown
                $cashAmount = $saleType === 'Cash' ? $netTotalAmount : rand(0, $netTotalAmount);
                $creditAmount = $saleType === 'Credit' ? $netTotalAmount : rand(0, $netTotalAmount - $cashAmount);
                $upiAmount = $saleType === 'UPI' ? $netTotalAmount : rand(0, $netTotalAmount - $cashAmount - $creditAmount);
                $cardAmount = $saleType === 'Card' ? $netTotalAmount : $netTotalAmount - $cashAmount - $creditAmount - $upiAmount;

                // Generate unique bill number
                $billNo = 'BILL' . str_pad($i + 1, 6, '0', STR_PAD_LEFT);

                // Create SaleMaster
                $saleMaster = SaleMaster::create([
                    'bill_no' => $billNo,
                    'sale_date' => $saleDate,
                    'sale_time' => $saleTime,
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'sale_type' => $saleType,
                    'gross_amount' => $grossAmount,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $grossAmount + $taxAmount,
                    'discount' => $discount,
                    'net_gross_amount' => $netGrossAmount,
                    'net_tax_amount' => $netTaxAmount,
                    'net_total_amount' => $netTotalAmount,
                    'narration' => 'Sample sale narration',
                    'cash_amount' => $cashAmount,
                    'credit_amount' => $creditAmount,
                    'upi_amount' => $upiAmount,
                    'card_amount' => $cardAmount,
                    'financial_year' => $financialYear,
                    'van_id' => $van->id,
                    'user_id' => $employee->id,
                ]);

                // Track negative stock items
                $negativeStockItems = [];
                $itemQuantities = [];
                $itemNames = [];

                // Create 1-5 Sale items per SaleMaster
                $numItems = rand(1, 5);
                for ($j = 0; $j < $numItems; $j++) {
                    $item = $items->random();
                    $itemUnitDetail = $itemUnitDetails[$item->id]->random();
                    $quantity = rand(1, 10);
                    $rate = rand(100, 1000);
                    $taxPercentage = rand(5, 18);
                    $grossAmount = $rate * $quantity;
                    $taxAmount = $grossAmount * ($taxPercentage / 100);
                    $totalAmount = $grossAmount + $taxAmount;

                    // Track quantities for negative stock check
                    if (!isset($itemQuantities[$item->id])) {
                        $itemQuantities[$item->id] = 0;
                        $itemNames[$item->id] = $item->name ?? 'Sample Item';
                    }
                    $itemQuantities[$item->id] += $quantity;

                    if ($item->current_stock < $itemQuantities[$item->id]) {
                        $negativeStockItems[] = [
                            'name' => $itemNames[$item->id],
                            'requested' => $itemQuantities[$item->id],
                            'available' => $item->current_stock,
                        ];
                    }

                    Sale::create([
                        'sale_master_id' => $saleMaster->id,
                        'bill_no' => $billNo,
                        'sale_date' => $saleDate,
                        'sale_time' => $saleTime,
                        'customer_id' => $customer->id,
                        'item_id' => $itemUnitDetail->id,
                        'item_name' => $item->name ?? 'Sample Item',
                        'rate' => $rate,
                        'unit_price' => $rate,
                        'quantity' => $quantity,
                        'total_quantity' => $quantity,
                        'unit_quantity' => 1,
                        'custom_quantity' => $quantity,
                        'unit' => $itemUnitDetail->unit_name ?? 'Unit',
                        'gross_amount' => $grossAmount,
                        'tax_amount' => $taxAmount,
                        'total_amount' => $totalAmount,
                        'tax_percentage' => $taxPercentage,
                        'narration' => 'Sample item narration',
                        'financial_year' => $financialYear,
                        'van_id' => $van->id,
                        'user_id' => $employee->id,
                    ]);

                    // Update stock
                    $item->current_stock -= $quantity;
                    $item->save();

                    ItemUnitDetail::where('default_item_id', $item->id)
                        ->decrement('stock', $quantity);
                }

            });
        }
    }
}
