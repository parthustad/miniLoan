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
        Schema::create('schedule_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('term');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('UNPAID');


            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('users');
            $table->foreign('loan_id')->references('id')->on('loans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_payments');
    }
};
