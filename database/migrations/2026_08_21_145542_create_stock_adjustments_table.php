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
        Schema::create('stock_adjustments', function (Blueprint $table) {
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
            | ADJUSTMENT
            |--------------------------------------------------------------------------
            */

            $table->enum('type', [
                'add',
                'remove',
                'set',
            ]);


            /*
            |--------------------------------------------------------------------------
            | QUANTITIES
            |--------------------------------------------------------------------------
            */

            $table->integer('quantity');

            $table->integer('previous_quantity');

            $table->integer('new_quantity');


            /*
            |--------------------------------------------------------------------------
            | REASON
            |--------------------------------------------------------------------------
            */

            $table->string('reason');


            /*
            |--------------------------------------------------------------------------
            | NOTES
            |--------------------------------------------------------------------------
            */

            $table->text('notes')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
