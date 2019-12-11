<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('short_url', 128)->nullable();
            $table->string('title')->nullable();
            $table->mediumText('content')->nullable();
            $table->string('video_title')->nullable();
            $table->text('video_description')->nullable();
            $table->string('remote_image')->nullable();
            $table->text('video_url')->nullable();
            $table->text('embed_url')->nullable();
            $table->text('embed_code')->nullable();
            $table->integer('embed_width')->nullable();
            $table->integer('embed_height')->nullable();
            $table->string('embed_aspect_ratio', 8)->nullable();

            $table->string('language', 5)->default('en');
            $table->string('timezone', 32)->default('UTC');

            $table->json('meta')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('active')->default(true);

            $table->integer('views')->unsigned()->default(0);

            // Image
            $table->string('image_file_name')->nullable();
            $table->integer('image_file_size')->nullable();
            $table->string('image_content_type')->nullable();
            $table->timestamp('image_updated_at')->nullable();

            // Icon
            $table->string('icon_file_name')->nullable();
            $table->integer('icon_file_size')->nullable();
            $table->string('icon_content_type')->nullable();
            $table->timestamp('icon_updated_at')->nullable();

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
        Schema::dropIfExists('videos');
    }
}
