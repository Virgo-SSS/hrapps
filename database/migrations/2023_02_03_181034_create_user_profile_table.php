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
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('divisi_id')->nullable();
            $table->foreign('divisi_id')->references('id')->on('divisi')->nullOnDelete();
            $table->unsignedBigInteger('posisi_id')->nullable();
            $table->foreign('posisi_id')->references('id')->on('posisi')->nullOnDelete();
            $table->integer('bank');
            $table->bigInteger('bank_account_number');
            $table->date('join_date');
            $table->integer('cuti');
            $table->bigInteger('salary');
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
        Schema::dropIfExists('user_profile');
    }
};
