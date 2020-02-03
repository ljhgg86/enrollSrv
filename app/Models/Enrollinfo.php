<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollinfo extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'enrollinfo';
    protected $fillable = ['readflag', 'delflag'];

    public function formiteminfos(){
        return $this->hasMany('App\Models\Formiteminfo','enrollinfo_id','id');
    }

    //获取活动用户提交信息
    public function getEnrollinfos($activityid){
        $activity = Activities::find($activityid);
        $infos = $this->where('activities_id',$activityid)
                    ->where('delflag',0)
                    ->with(['formiteminfos'=>function($query){
                        $query->where('delflag',0)
                                ->with('formitem');
                    }])
                    ->paginate(15);
        $infos = json_decode(json_encode($infos),true);
        $enrollItems = array();
        foreach($infos['data'] as $enrollItem){
            $enrollItemInfo = array();
            $enrollItemMedia = array();
            foreach($enrollItem['formiteminfos'] as $formiteminfo){
                if(strpos($formiteminfo['formitem']['formtagname'], "media")){
                    array_push($enrollItemMedia, $formiteminfo['formitem']['name'].":".$formiteminfo['info']);
                }
                else{
                    array_push($enrollItemInfo, $formiteminfo['formitem']['name'].":".$formiteminfo['info']);
                }
            }
            array_push($enrollItems,
                     array("id"=>$enrollItem['id'],
                            "readflag"=>$enrollItem['readflag'],
                            "info"=>$enrollItemInfo,
                            "media"=>$enrollItemMedia
                        )
                    );
        }
        return array(
            'total'=>$infos['total'],
            'per_page'=>$infos['per_page'],
            'infos'=>$enrollItems,
            'title'=>$activity->title
        );
    }
}
