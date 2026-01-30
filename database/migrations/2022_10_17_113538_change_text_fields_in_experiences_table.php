<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTextFieldsInExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('description_ru')->change();
            $table->string('description_en')->change();
            $table->MediumText('duties_ru')->change();
            $table->MediumText('duties_en')->change();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->LongText('description_ru')->change();
            $table->LongText('description_en')->change();
            $table->string('duties_ru')->change();
            $table->string('duties_en')->change();
        });
    }
}
