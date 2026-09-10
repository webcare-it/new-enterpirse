<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpConfigurationsTable extends Migration
{
    public function up()
    {
        Schema::create('otp_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('otp_configurations');
    }
}
