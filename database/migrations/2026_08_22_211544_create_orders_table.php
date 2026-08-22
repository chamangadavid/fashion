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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
             /*
            |--------------------------------------------------------------------------
            | CUSTOMER
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | ORDER INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('order_number')
                ->unique();


            $table->enum('status', [
                'pending',
                'processing',
                'completed',
                'cancelled',
                'refunded',
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | CUSTOMER CONTACT
            |--------------------------------------------------------------------------
            */

            $table->string('customer_email');

            $table->string('customer_phone');


            /*
            |--------------------------------------------------------------------------
            | SHIPPING INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('shipping_first_name');

            $table->string('shipping_last_name');

            $table->string('shipping_address');

            $table->string('shipping_city');

            $table->string('shipping_country');


            /*
            |--------------------------------------------------------------------------
            | ORDER TOTALS
            |--------------------------------------------------------------------------
            */

            $table->decimal('subtotal', 12, 2)
                ->default(0);

            $table->decimal('shipping_amount', 12, 2)
                ->default(0);

            $table->decimal('total_amount', 12, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_method', [
                'cash_on_delivery',
                'mobile_money',
                'card',
            ])->default('cash_on_delivery');


            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'cancelled',
                'refunded',
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | PAYMENT REFERENCE
            |--------------------------------------------------------------------------
            */

            $table->string('payment_reference')
                ->nullable();


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
        Schema::dropIfExists('orders');
    }
};
