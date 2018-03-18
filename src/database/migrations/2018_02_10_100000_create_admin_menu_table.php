<?php

use Revys\Revy\App\Entity;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('controller');
            $table->string('action');
            $table->string('icon')->nullable();
            $table->tinyInteger('status')->default(Entity::STATUS_PUBLISHED);
            $table->timestamps();
        });

        Schema::create('admin_menu_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_menu_id')->unsigned();
            $table->string('locale')->index();
            
            $table->string('title');
        
            $table->unique(['admin_menu_id','locale']);
            $table->foreign('admin_menu_id')->references('id')->on('admin_menu')->onDelete('cascade');
        });

        Schema::table('admin_menu', function($table) {
            $table->foreign('parent_id')->references('id')->on('admin_menu')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_menu_translations');
        Schema::dropIfExists('admin_menu');
    }
}
