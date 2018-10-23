<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoanDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('master_id');
            $table->integer('payment_no');
            $table->date('payment_date');
            $table->decimal('payment_amount', 21, 6);
            $table->float('principal', 21, 6);
            $table->float('interest', 21, 6);
            $table->float('balance', 21, 6);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     *
     *
     *
     */
    public function down()
    {
        Schema::dropIfExists('loan_details');
    }
}
