<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //活动
        if (!Schema::hasTable('activities')) {
            Schema::create('activities', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title',255)->comment('标题');
                $table->string('topimg',255)->comment('封面图');
                $table->timestamps();
            });
        }


        //标签
        if (!Schema::hasTable('formtag')) {
            Schema::create('formtag', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name',50);
                $table->string('tag',50);
                $table->timestamps();
            });
        }


        //输入类型
        if (!Schema::hasTable('inputtype')) {
            Schema::create('inputtype', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name',50);
                $table->string('tag',50);
                $table->timestamps();
            });
        }


        //活动项
        if (!Schema::hasTable('formitem')) {
            Schema::create('formitem', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name',255)->comment('名称');
                $table->integer('activities_id');
                $table->integer('formtag_id');
                $table->integer('need')->default(0)->comment('0选填，1必填');
                $table->integer('order')->default(0)->comment('排序');
                $table->integer('delflag')->default(0);
                $table->index('activities_id');
                $table->index('formtag_id');
                $table->timestamps();
            });
        }


        //活动项子项option/radio/checkbox等
        if (!Schema::hasTable('childformitem')) {
            Schema::create('childformitem', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name',255)->comment('子项内容');
                $table->integer('inputtype_id');
                $table->integer('formitem_id');
                $table->integer('order')->default(0)->comment('排序');
                $table->integer('delflag')->default(0);
                $table->integer('defaultflag')->default(0)->comment('1默认选中');
                $table->index('inputtype_id');
                $table->index('formitem_id');
                $table->timestamps();
            });
        }


        //报名信息
        if (!Schema::hasTable('enrollinfo')) {
            Schema::create('enrollinfo', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('activities_id');
                $table->integer('readflag')->default(0)->comment('0未读，1已读');
                $table->integer('delflag')->default(0);
                $table->index('activities_id');
                $table->timestamps();
            });
        }


        //报名项信息
        if (!Schema::hasTable('formiteminfo')) {
            Schema::create('formiteminfo', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('info',1000)->comment('报名项内容');
                $table->integer('enrollinfo_id');
                $table->integer('formitem_id');
                $table->integer('delflag')->default(0);
                $table->index(['enrollinfo_id', 'formitem_id']);
                $table->timestamps();
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
        Schema::dropIfExists('activities');
        Schema::dropIfExists('formtag');
        Schema::dropIfExists('inputtype');
        Schema::dropIfExists('formitem');
        Schema::dropIfExists('childformitem');
        Schema::dropIfExists('enrollinfo');
        Schema::dropIfExists('formiteminfo');
    }
}
