<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('droploo_product_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->foreignId('added_by');
            // $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->foreignId('user_id');
            $table->foreignId('brand_id')->nullable();
            $table->foreignId('category_id')->nullable();
            $table->foreignId('subcategory_id')->nullable();
            $table->string('thumbnail')->nullable();
            $table->longText('photos')->nullable();
            $table->longText('tags')->nullable();
            $table->longText('description')->nullable();
            $table->text('short_description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_cat')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('unit')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('num_of_sale')->default(0);
            $table->string('video_link')->nullable();
            $table->boolean('is_variant')->default(false);
            $table->string('badge_name')->nullable();
            $table->integer('batch_no')->nullable();
            $table->decimal('todays_deal', 10, 2)->default(0);
            $table->boolean('best_selling')->default(false);
            $table->integer('position')->default(0);
            $table->boolean('is_new_arrival')->default(false);
            $table->integer('stock_request')->default(0);
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
        Schema::dropIfExists('products');
    }
}
