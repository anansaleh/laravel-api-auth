<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('transaction_id');
            $table->unsignedInteger('customer_id')->comment('F.K related to customers table');
            $table->decimal('amount',10,2)->comment('The transaction amount.');
            $table->unsignedInteger('created_by')->nullable()->comment('F.K. Related to the user id who create transaction.');
            $table->unsignedInteger('updated_by')->nullable()->comment('F.K. Related to the user id who update transaction.');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->foreign('customer_id', 'FK_transactions_customer_id')->references('customer_id')->on('customers')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('created_by', 'FK_transactions_created_by')->references('user_id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('updated_by', 'FK_transactions_updated_by')->references('user_id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
