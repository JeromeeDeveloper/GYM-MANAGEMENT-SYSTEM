<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {

            $table->id();
            $table->string('code');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('phone');
            // $table->integer('age');
            // $table->enum('gender', ['male', 'female'])->nullable();
            $table->timestamps();
            // $table->date('birthdate')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
}
