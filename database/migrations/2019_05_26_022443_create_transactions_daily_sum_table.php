<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsDailySumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions_daily_sum', function (Blueprint $table) {
            $table->increments('trans_daily_id');
            $table->timestamp('date_from')->nullable();
            $table->timestamp('date_to')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->decimal('sum_amount',10,2);
            //$table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions_daily_sum');
    }
}
