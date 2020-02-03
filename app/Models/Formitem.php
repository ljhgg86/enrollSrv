<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formitem extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'formitem';

    protected $fillable = ['name', 'activities_id', 'formtagname', 'need', 'order', 'media_ex'];

    public function activities(){
        return $this->belongsTo('App\Models\Activities','activities_id');
    }

    public function formtag(){
        return $this->belongsTo('App\Models\Formtag','formtag_id');
    }

    public function childformitems(){
    	return $this->hasMany('App\Models\Childformitem','formitem_id','id');
    }

    public function formiteminfos(){
        return $this->hasMany('App\Models\Formiteminfo','formitem_id','id');
    }

    public function saveFormitems($formitems, $activity_id){
        for($i=0;$i<count($formitems);$i++){
            $mediaEx = $formitems[$i]['mediaOptions'];
            for($j=0;$j<count($mediaEx);$j++){
                $mediaEx[$j] = $mediaEx[$j] ? 1 : 0;
            }
            array_push($mediaEx,$formitems[$i]['mediaCount']);
            if($formitems[$i]['id']){
                $formitemid = $formitems[$i]['id'];
                $this->where('id',$formitems[$i]['id'])
                        ->update([
                            'name'=>$formitems[$i]['name'],
                            'formtagname'=>implode(",",$formitems[$i]['formtagname']),
                            'need'=>$formitems[$i]['need'],
                            'order'=>$i+1,
                            'media_ex'=>implode(",",$mediaEx)
                        ]);
            }
            else{
                $formitemTemp = $this->create([
                                    'name'=>$formitems[$i]['name'],
                                    'activities_id'=>$activity_id,
                                    'formtagname'=>implode(",",$formitems[$i]['formtagname']),
                                    'need'=>$formitems[$i]['need'],
                                    'order'=>$i+1,
                                    'media_ex'=>implode(",",$mediaEx)
                                ]);
                $formitemid = $formitemTemp->id;
            }
            if(!empty($formitems[$i]['childformitems'])){
                $childFormitem = new Childformitem();
                $childFormitem->saveChildformitems($formitems[$i]['childformitems'], $formitemid);
            }
        }
    }
}
