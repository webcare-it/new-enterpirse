<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandingpagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landingpages', function (Blueprint $table) {
            $table->id();
            // Basic Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            // Hero Section
            $table->string('banner_image')->nullable();
            $table->string('mobile_banner')->nullable();
            $table->string('video_link')->nullable();
            // Countdown
            $table->dateTime('deadline')->nullable();
            // Features
            $table->json('features')->nullable();
            // Content
            $table->longText('description')->nullable();
            $table->text('short_description')->nullable();
            // Testimonials
            $table->longText('testimonials')->nullable();
            // FAQ
            $table->longText('faq')->nullable();
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_image')->nullable();
            // Social Proof
            $table->integer('sold_count')->default(0);
            $table->integer('visitor_count')->default(0);
            // Contact
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            // Footer
            $table->string('copyright_text')->nullable();
            // Status
            $table->boolean('is_published')->default(true);
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
        Schema::dropIfExists('landingpages');
    }
}
