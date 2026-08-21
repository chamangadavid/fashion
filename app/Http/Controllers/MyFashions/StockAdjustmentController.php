<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    
/**
     * Store stock adjustment.
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => [
                'required',
                'in:add,remove,set',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            'reason' => [
                'required',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        DB::transaction(function () use (
            $validated,
            $product
        ) {

            /*
            |--------------------------------------------------------------------------
            | CURRENT STOCK
            |--------------------------------------------------------------------------
            */

            $previousQuantity = $product->stock_quantity;


            /*
            |--------------------------------------------------------------------------
            | CALCULATE NEW STOCK
            |--------------------------------------------------------------------------
            */

            switch ($validated['type']) {

                case 'add':

                    $newQuantity =
                        $previousQuantity +
                        $validated['quantity'];

                    break;


                case 'remove':

                    $newQuantity =
                        $previousQuantity -
                        $validated['quantity'];

                    break;


                case 'set':

                    $newQuantity =
                        $validated['quantity'];

                    break;


                default:

                    $newQuantity = $previousQuantity;

                    break;
            }


            /*
            |--------------------------------------------------------------------------
            | PREVENT NEGATIVE STOCK
            |--------------------------------------------------------------------------
            */

            if ($newQuantity < 0) {

                abort(
                    422,
                    'Stock quantity cannot be negative.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE PRODUCT
            |--------------------------------------------------------------------------
            */

            $product->update([
                'stock_quantity' => $newQuantity,
            ]);


            /*
            |--------------------------------------------------------------------------
            | RECORD ADJUSTMENT
            |--------------------------------------------------------------------------
            */

            StockAdjustment::create([

                'product_id' => $product->id,

                'user_id' => Auth::id(),

                'type' => $validated['type'],

                'quantity' => $validated['quantity'],

                'previous_quantity' => $previousQuantity,

                'new_quantity' => $newQuantity,

                'reason' => $validated['reason'],

                'notes' => $validated['notes'] ?? null,

            ]);
        });


        return back()->with(
            'success',
            'Stock adjusted successfully.'
        );
    }
}
