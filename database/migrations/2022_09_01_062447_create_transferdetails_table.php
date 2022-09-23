<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferdetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to');
            $table->text('amountsend');
            $table->foreign('from')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('transferdetails');
    }
}
