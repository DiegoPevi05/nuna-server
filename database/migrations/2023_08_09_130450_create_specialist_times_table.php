<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialist_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specialist_id')->nullable(false);
            $table->foreign('specialist_id')->references('id')->on('specialists')->onDelete('cascade');
            $table->datetime('start_date')->nullable(false);
            $table->datetime('end_date')->nullable(false);
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
        Schema::dropIfExists('specialist_times');
    }
};
