<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceConfigItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_config_items', function (Blueprint $table) {
            $table->id();
            $table->integer('config_id');
            $table->string('key');
            $table->string('type');
            $table->text('value');
            $table->tinyInteger('is_edit')->default(1);
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
        Schema::dropIfExists('source_config_items');
    }
}
