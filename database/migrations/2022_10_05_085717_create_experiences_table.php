<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('start');
            $table->date('end')->nullable();
            $table->string('logo');
            $table->string('name_ru');
            $table->string('name_en');
            $table->string('url');
            $table->longText('description_ru');
            $table->longText('description_en');
            $table->string('position_ru');            
            $table->string('position_en');            
            $table->string('duties_ru');
            $table->string('duties_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experiences');
    }
}
