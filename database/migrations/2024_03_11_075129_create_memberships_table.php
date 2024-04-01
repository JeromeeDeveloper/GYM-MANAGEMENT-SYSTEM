<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipsTable extends Migration
{
    public function up()
    {
        Schema::create('membership', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('annual_start_date')->nullable();
            $table->date('annual_end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired'])->nullable();
            $table->enum('annual_status', ['active', 'inactive', 'cancelled'])->nullable();
            $table->timestamps();
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');


        });
    }

    public function down()
    {
        Schema::dropIfExists('memberships');
    }
}
