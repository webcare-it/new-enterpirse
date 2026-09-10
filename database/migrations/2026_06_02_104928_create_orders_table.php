<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('combined_order_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('guest_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();

            $table->longText('shipping_address')->nullable();
            $table->string('delivery_status')->default('pending');

            $table->string('is_otp_verified')->nullable();
            $table->string('payment_type')->nullable();
            $table->boolean('manual_payment')->default(false);
            $table->longText('manual_payment_data')->nullable();

            $table->string('payment_status')->default('unpaid');
            $table->longText('payment_details')->nullable();

            $table->decimal('grand_total', 20, 2)->default(0);
            $table->decimal('shipping_cost', 20, 2)->default(0);
            $table->decimal('coupon_discount', 20, 2)->default(0);
            $table->decimal('discount', 20, 2)->default(0);

            $table->string('code')->nullable();
            $table->string('tracking_code')->nullable();
            $table->text('notes')->nullable();
            $table->bigInteger('date')->nullable();

            $table->string('courier_name')->nullable();
            $table->string('consignment_id')->nullable();
            $table->string('courier_tracking_code')->nullable();

            $table->boolean('viewed')->default(false);
            $table->boolean('delivery_viewed')->default(false);
            $table->boolean('payment_status_viewed')->default(false);
            $table->boolean('commission_calculated')->default(false);

            $table->string('shipping_type')->nullable();
            $table->string('order_type')->nullable(); // physical, regular
            $table->string('email_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->index('user_id');
            $table->index('seller_id');
            $table->index('combined_order_id');
            $table->index('payment_status');
            $table->index('delivery_status');
            $table->index('code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
