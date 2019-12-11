<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('short_url', 128)->nullable();

            $table->string('qr_url', 250)->nullable();
            $table->text('redirect_to_url')->nullable();

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('icon')->nullable();
            $table->mediumText('content')->nullable();

            $table->string('language', 5)->default('en');
            $table->string('timezone', 32)->default('UTC');

            $table->json('meta')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('active')->default(true);

            $table->integer('views')->unsigned()->default(0);

            // QR
            $table->string('qr_file_name')->nullable();
            $table->integer('qr_file_size')->nullable();
            $table->string('qr_content_type')->nullable();
            $table->timestamp('qr_updated_at')->nullable();

            // Image
            $table->string('image_file_name')->nullable();
            $table->integer('image_file_size')->nullable();
            $table->string('image_content_type')->nullable();
            $table->timestamp('image_updated_at')->nullable();

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
        Schema::dropIfExists('qr_codes');
    }
}
