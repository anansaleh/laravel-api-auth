<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('customer_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->unsignedInteger('created_by')->nullable()->comment('F.K. Related to the user id who create customer.');
            $table->unsignedInteger('updated_by')->nullable()->comment('F.K. Related to the user id who update customer.');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->foreign('created_by', 'FK_customers_created_by')->references('user_id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('updated_by', 'FK_customers_updated_by')->references('user_id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
