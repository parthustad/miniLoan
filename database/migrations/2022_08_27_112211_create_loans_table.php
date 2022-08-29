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
        Schema::create('loans', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('client_id');
            $table->decimal('amount', 10, 2);
            $table->unsignedInteger('term');
            $table->unsignedBigInteger('reviewer_id')->default(1);
            $table->string('loan_status')->default('PENDING');
            $table->timestamp('disbursed_at')->nullable();
            $table->decimal('min_payment', 10, 2)->default(0.00);
            $table->decimal('total_paid', 10, 2)->default(0.00);
            $table->decimal('extra_amount', 10, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('users');
            //$table->foreign('reviewer_id')->references('id')->on('users');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
