<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('thumbnail', 120)->nullable();
            $table->string('slug', 120)->nullable();
            $table->string('main_image', 120)->nullable();
            $table->text('tags')->nullable();
            $table->string('blog_title', 150)->nullable();
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->string('user_id', 100)->default(1);

            $table->string('meta_title', 150)->nullable();
            $table->string('meta_image', 120)->nullable();
            $table->string('meta_description', 255)->nullable();
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
        Schema::dropIfExists('blogs');
    }
}
