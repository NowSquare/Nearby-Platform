<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_cards', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('short_url', 128)->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('pdf')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('website')->nullable();
            $table->string('phone', 24)->nullable();
            $table->text('details')->nullable();
            $table->mediumText('content')->nullable();
            $table->string('address')->nullable();
            $table->string('street')->nullable();
            $table->string('street_number', 12)->nullable();
            $table->string('postal_code', 12)->nullable();
            $table->string('city', 128)->nullable();
            $table->string('state', 64)->nullable();
            $table->string('country', 5)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();

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

            // Avatar
            $table->string('avatar_file_name')->nullable();
            $table->integer('avatar_file_size')->nullable();
            $table->string('avatar_content_type')->nullable();
            $table->timestamp('avatar_updated_at')->nullable();

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
        Schema::dropIfExists('business_cards');
    }
}
