<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_recipient', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('email_id');
            $table->unsignedBigInteger('recipient_id');
            $table->string('status')->nullable();
            $table->string('service_provider')->nullable();
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
        Schema::dropIfExists('email_recipient');
    }
}
