<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFormtagidToInputtypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('inputtype')){
            Schema::table('inputtype', function(Blueprint $table){
                $table->integer('formtag_id')->after('tag');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inputtype', function (Blueprint $table) {
            if (Schema::hasColumn('inputtype', 'formtag_id')) {
                $table->dropColumn('formtag_id');
            }
        });
    }
}
