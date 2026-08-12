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
        Schema::create('incomplete_orders', function (Blueprint $table) {
            $table->id();

            // Order reference
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('session_id')->nullable();
            $table->string('order_code')->unique();

            // Customer information
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();

            // Order details
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            // Status
            $table->enum('status', [
                'pending',
                'abandoned',
                'processing',
                'completed',
                'cancelled'
            ])->default('pending');

            // Cart data (JSON)
            $table->json('cart_data')->nullable();

            // Tracking
            $table->timestamp('last_activity')->nullable();
            $table->timestamp('abandoned_at')->nullable();
            $table->integer('reminder_count')->default(0);
            $table->timestamp('last_reminder_sent_at')->nullable();

            // Notes
            $table->text('notes')->nullable();

            // User who created/modified
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('order_id');
            $table->index('session_id');
            $table->index('order_code');
            $table->index('customer_email');
            $table->index('customer_phone');
            $table->index('status');
            $table->index('last_activity');
            $table->index('abandoned_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomplete_orders');
    }
};
