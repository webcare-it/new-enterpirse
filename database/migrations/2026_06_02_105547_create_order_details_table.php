<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->string('variation')->nullable();
            $table->decimal('price', 20, 2)->default(0);
            $table->decimal('tax', 20, 2)->default(0);
            $table->decimal('shipping_cost', 20, 2)->default(0);
            $table->integer('quantity')->default(1);

            $table->string('payment_status')->default('unpaid');
            $table->string('delivery_status')->default('pending');
            $table->string('shipping_type')->nullable();
            $table->unsignedBigInteger('pickup_point_id')->nullable();
            $table->string('product_referral_code')->nullable();


            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
            $table->index('seller_id');
            $table->index('payment_status');
            $table->index('delivery_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
