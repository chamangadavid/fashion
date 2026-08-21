<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_audits', function (Blueprint $table) {
            $table->id();
             /*
            |--------------------------------------------------------------------------
            | PRODUCT
            |--------------------------------------------------------------------------
            */

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | STOCK INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->integer('previous_quantity');

            $table->integer('adjustment_quantity');

            $table->integer('new_quantity');


            /*
            |--------------------------------------------------------------------------
            | ADJUSTMENT TYPE
            |--------------------------------------------------------------------------
            */

            $table->enum('adjustment_type', [
                'restock',
                'sale',
                'return',
                'damage',
                'loss',
                'correction',
                'initial_stock',
                'other',
            ]);


            /*
            |--------------------------------------------------------------------------
            | REASON
            |--------------------------------------------------------------------------
            */

            $table->string('reason')->nullable();


            /*
            |--------------------------------------------------------------------------
            | REFERENCE
            |--------------------------------------------------------------------------
            */

            $table->string('reference')->nullable();


            /*
            |--------------------------------------------------------------------------
            | NOTES
            |--------------------------------------------------------------------------
            */

            $table->text('notes')->nullable();


            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_audits');
    }
};
