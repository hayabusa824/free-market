<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image');
            $table->string('category');
            $table->string('condition');
            $table->string('name');
            $table->string('brand')->nullable();
            $table->text('description');
            $table->integer('price');
            $table->boolean('is_sold')->default(false);
            $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('comment_count')->default(0);
            $table->integer('like_count')->default(0);
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
        Schema::dropIfExists('items');
    }
}
