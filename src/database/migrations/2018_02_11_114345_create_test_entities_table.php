<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! App::runningUnitTests())
            return null;

        Schema::create('test_entities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('string_field');
            $table->integer('int_field');
            $table->date('date_field');
            $table->timestamps();
        });

        Schema::create('test_entity_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('test_entity_id')->unsigned();
            $table->string('locale')->index();

            $table->string('multilang_field');

            $table->unique(['test_entity_id','locale']);
            $table->foreign('test_entity_id')->references('id')->on('test_entities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! App::runningUnitTests())
            return null;

        Schema::dropIfExists('test_entities');
        Schema::dropIfExists('test_entities_translations');
    }
}
