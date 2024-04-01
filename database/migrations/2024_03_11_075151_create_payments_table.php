<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->decimal('amount', 10, 2);
            // $table->date('payment_date')->nullable();
            $table->timestamps();
            $table->enum('type', ['gcash','cash'])->default('cash');
            $table->enum('payment_for', ['session','monthly','bi-monthly','6-months','1-year','Annual-Fee'])->default('Annual-Fee');
            $table->string('transaction_code')->nullable();
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
