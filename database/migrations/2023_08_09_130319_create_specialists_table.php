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
        Schema::create('specialists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->json('services')->nullable(false);
            $table->string('address',255)->nullable();
            $table->string('phone_number')->nullable();
            $table->enum('sex',['M','F','O'])->default('M');
            $table->text('profile_image')->nullable();
            $table->enum('type_document',['DNI','PASSPORT'])->default('DNI');
            $table->integer('document_id')->nullable();
            $table->text('summary')->nullable();
            $table->json('awards')->nullable();
            $table->json('experiences')->nullable();
            $table->json('educations')->nullable();
            $table->float('evaluated_rate')->nullable();
            $table->boolean('is_active')->nullable();
            $table->date('birthdate')->nullable();
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
        Schema::dropIfExists('specialists');
    }
};
