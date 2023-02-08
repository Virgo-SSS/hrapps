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
        Schema::create('cuti_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuti_id')->constrained('cuti')->cascadeOnDelete();

            $table->unsignedBigInteger('head_of_division')->nullable();
            $table->foreign('head_of_division')->references('id')->on('users')->nullOnDelete();
            $table->integer('status_hod')->default(0);
            $table->text('note_hod')->nullable();
            $table->dateTime('approved_hod_at')->nullable();

            $table->unsignedBigInteger('head_of_department')->nullable();
            $table->foreign('head_of_department')->references('id')->on('users')->nullOnDelete();
            $table->integer('status_hodp')->default(0);
            $table->text('note_hodp')->nullable();
            $table->dateTime('approved_hodp_at')->nullable();
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
        Schema::dropIfExists('request_cuti');
    }
};
