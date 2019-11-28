<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeToActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('activities')) {
            Schema::table('activities', function (Blueprint $table) {
                $table->timestamp('begintime')->nullable()->after('topimg')->comment('开始时间');
                $table->timestamp('endtime')->nullable()->after('begintime')->comment('结束时间');
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
        Schema::table('activities', function (Blueprint $table) {
            if (Schema::hasColumn('activities', 'begintime')) {
                $table->dropColumn('begintime');
            }

            if (Schema::hasColumn('activities', 'endtime')) {
                $table->dropColumn('endtime');
            }
        });
    }
}
