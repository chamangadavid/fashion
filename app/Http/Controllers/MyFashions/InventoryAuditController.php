<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryAudit;
use Inertia\Inertia;

class InventoryAuditController extends Controller
{
    
/**
     * Display inventory audit trail.
     */
    public function index(Request $request)
    {
        $query = InventoryAudit::with([
            'product',
            'user',
        ])
        ->latest();


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('reason', 'like', "%{$search}%")

                    ->orWhere('reference', 'like', "%{$search}%")

                    ->orWhere('adjustment_type', 'like', "%{$search}%")

                    ->orWhereHas('product', function ($productQuery) use ($search) {

                        $productQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");

                    });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | ADJUSTMENT TYPE FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('adjustment_type')) {

            $query->where(
                'adjustment_type',
                $request->adjustment_type
            );

        }


        /*
        |--------------------------------------------------------------------------
        | PRODUCT FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('product_id')) {

            $query->where(
                'product_id',
                $request->product_id
            );

        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $audits = $query
            ->paginate(20)
            ->withQueryString();


        return Inertia::render(
            'MyFashions/Products/InventoryAudit',
            [
                'audits' => $audits,

                'filters' => [
                    'search' => $request->search,
                    'adjustment_type' => $request->adjustment_type,
                    'product_id' => $request->product_id,
                ],
            ]
        );
    }

    public function auditDetails(InventoryAudit $audit)
{
    $audit->load([
        'product',
        'user',
    ]);

    return response()->json([
        'success' => true,

        'audit' => [
            'id' => $audit->id,

            'product' => [
                'id' => $audit->product?->id,
                'name' => $audit->product?->name,
                'sku' => $audit->product?->sku,
                'image' => $audit->product?->image,
            ],

            'user' => [
                'id' => $audit->user?->id,
                'name' => $audit->user?->name,
                'email' => $audit->user?->email,
            ],

            'type' => $audit->type,

            'quantity_before' => $audit->quantity_before,

            'quantity_change' => $audit->quantity_change,

            'quantity_after' => $audit->quantity_after,

            'reason' => $audit->reason,

            'notes' => $audit->notes,

            'created_at' => $audit->created_at,

            'updated_at' => $audit->updated_at,
        ],
    ]);
}


}
