<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('property_type_id')->nullable();
            $table->integer('property_sales_type_id')->nullable();

            $table->string('title')->nullable();
            $table->text('short_description')->nullable();
            $table->mediumText('description')->nullable();
            $table->integer('price_sale')->unsigned()->nullable();
            $table->integer('price_rent')->unsigned()->nullable();
            $table->datetime('last_price_change')->nullable();
            $table->integer('previous_price')->nullable()->unsigned();
            $table->char('currency_code', 3)->nullable();
            $table->string('website')->nullable();
            $table->string('phone', 24)->nullable();

            $table->string('address')->nullable();
            $table->string('street')->nullable();
            $table->string('street_number', 12)->nullable();
            $table->string('postal_code', 12)->nullable();
            $table->string('city', 128)->nullable();
            $table->string('state', 64)->nullable();
            $table->string('country', 5)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();

            $table->string('building_type', 100);
            $table->integer('year_of_construction')->unsigned()->nullable();
            $table->string('unit', 3)->nullable(); // m2 / ft2
            $table->integer('living_area')->unsigned()->nullable();
            $table->integer('external_storage_space')->unsigned()->nullable();
            $table->integer('plot_size')->unsigned()->nullable();
            $table->integer('volume')->unsigned()->nullable();
            $table->boolean('is_new')->default(0); // false = resale / true = new
            $table->boolean('business')->default(0);
            $table->tinyInteger('floor')->unsigned()->nullable();
            $table->tinyInteger('floors')->unsigned()->nullable();
            $table->tinyInteger('rooms')->unsigned()->nullable();
            $table->tinyInteger('beds')->unsigned()->nullable();
            $table->decimal('baths', 3, 1)->unsigned()->nullable();
            $table->tinyInteger('car_spaces')->nullable();

            $table->boolean('for_sale')->default(0);
            $table->boolean('for_rent')->default(0);
            $table->boolean('pet_allowed')->default(0);
            $table->boolean('dishwasher')->default(0);
            $table->boolean('furnished')->default(0);
            $table->boolean('sold')->default(0);
            $table->datetime('on_market_since')->nullable();
            $table->datetime('sold_at')->nullable();
            $table->text('ext_url')->nullable();

            // Image
            $table->string('image_file_name')->nullable();
            $table->integer('image_file_size')->nullable();
            $table->string('image_content_type')->nullable();
            $table->timestamp('image_updated_at')->nullable();

            $table->string('language', 5)->default('en');
            $table->string('timezone', 32)->default('UTC');

            $table->json('meta')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();
        });

        Schema::create('property_surroundings', function(Blueprint $table)
        {
          $table->increments('id');
          $table->string('name', 32)->unique();
        });

        // Many-to-many relation
        Schema::create('property_surrounding', function(Blueprint $table)
        {
          $table->increments('id');
          $table->integer('property_id')->unsigned();
          $table->integer('property_surrounding_id')->unsigned();
        });

        Schema::table('property_surrounding', function($table) {
          $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
          $table->foreign('property_surrounding_id')->references('id')->on('property_surroundings')->onDelete('cascade');
        });

        Schema::create('property_features', function(Blueprint $table)
        {
          $table->increments('id');
          $table->string('name', 32)->unique();
        });

        // Many-to-many relation
        Schema::create('property_feature', function(Blueprint $table)
        {
          $table->increments('id');
          $table->integer('property_id')->unsigned();
          $table->integer('property_feature_id')->unsigned();
          $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
          $table->foreign('property_feature_id')->references('id')->on('property_features')->onDelete('cascade');
        });

        Schema::create('property_garages', function(Blueprint $table)
        {
          $table->increments('id');
          $table->string('name', 32)->unique();
        });

        // Many-to-many relation
        Schema::create('property_garage', function(Blueprint $table)
        {
          $table->increments('id');
          $table->integer('property_id')->unsigned();
          $table->integer('property_garage_id')->unsigned();
          $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
          $table->foreign('property_garage_id')->references('id')->on('property_garages')->onDelete('cascade');
        });

        Schema::create('property_photos', function(Blueprint $table)
        {
          $table->increments('id');
          $table->integer('order');
          $table->integer('property_id')->unsigned();
          $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
          $table->string('name', 100)->nullable();

          // Photo
          $table->string('photo_file_name')->nullable();
          $table->integer('photo_file_size')->nullable();
          $table->string('photo_content_type')->nullable();
          $table->timestamp('photo_updated_at')->nullable();

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
        Schema::dropIfExists('property_photos');
        Schema::dropIfExists('property_garage');
        Schema::dropIfExists('property_garages');
        Schema::dropIfExists('property_feature');
        Schema::dropIfExists('property_features');
        Schema::dropIfExists('property_surrounding');
        Schema::dropIfExists('property_surroundings');
        Schema::dropIfExists('properties');
    }
}
