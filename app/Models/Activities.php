<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
     /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'activities';

    protected $fillable = ['title', 'topimg', 'begintime', 'endtime'];

    public function formitems(){
    	return $this->hasMany('App\Models\Formitem','activities_id','id');
    }

    //获取活动编辑信息
    public function getActivities($id){
        return $this->where('id',$id)
                    ->with(['formitems'=>function($query){
                        $query->where('delflag',0)
                                ->with(['childformitems'=>function($query){
                                    $query->where('delflag',0)
                                            ->orderBy('order')
                                            ->orderBy('id');
                                }])
                                ->orderBy('order')
                                ->orderBy('id');
                    }])
                    ->first();
    }

}

