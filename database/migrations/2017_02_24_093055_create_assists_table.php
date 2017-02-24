<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('province')->default(1);
            $table->integer('type_of_assistance')->default(1);
            $table->string('fr_name');
            $table->string('address');
            $table->string('date_submitted');
            $table->decimal('amount', 10, 2);
            $table->text('action_taken');
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
        Schema::dropIfExists('assists');
    }
}
