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
        Schema::create('betails', function (Blueprint $table) {
            $table->id();
            $table->integer('age');
            $table->float('taille');
            $table->float('poids');
            $table->float('prix');
            $table->string('status');
            $table->string('description');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('betails');
    }
};
