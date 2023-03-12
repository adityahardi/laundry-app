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
        Schema::create('tambahan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tambahan_id')->constrained('tambahans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tambahan_details');
    }
};
