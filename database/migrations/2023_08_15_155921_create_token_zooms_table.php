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
        Schema::create('token_zooms', function (Blueprint $table) {
            $table->id();
            $table->string('CLIENT_ID_ZOOM',2500);
            $table->string('CLIENT_SECRET_ZOOM',2500);
            $table->string('access_token',2500);
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
        Schema::dropIfExists('token_zooms');
    }
};
