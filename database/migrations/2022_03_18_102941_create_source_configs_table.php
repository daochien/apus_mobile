<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('source_id');
            $table->string('key');
            $table->string('type')->nullable();
            $table->string('value')->nullable();
            $table->tinyInteger('is_edit')->nullable();
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
        Schema::dropIfExists('source_configs');
    }
}
