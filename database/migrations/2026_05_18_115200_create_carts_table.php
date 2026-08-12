<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('temp_user_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->string('variation')->nullable();
            $table->double('price', 20, 2)->default(0);
            $table->double('tax', 20, 2)->default(0);
            $table->double('shipping_cost', 20, 2)->default(0);
            $table->string('shipping_type')->nullable();
            $table->unsignedBigInteger('pickup_point')->nullable();
            $table->double('discount', 20, 2)->default(0);
            $table->string('product_referral_code')->nullable();
            $table->string('coupon_code')->nullable();
            $table->float('coupon_discount', 10, 2)->nullable();
            $table->boolean('coupon_applied')->default(0);
            $table->integer('quantity')->default(1);
            $table->timestamps();
            $table->index('owner_id');
            $table->index('user_id');
            $table->index('temp_user_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
