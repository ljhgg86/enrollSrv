<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Childformitem extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'childformitem';

    protected $fillable = ['name', 'formitem_id', 'order', 'defaultflag'];

    public function formitem(){
        return $this->belongsTo('App\Models\Formitem','formitem_id');
    }

    public function saveChildformitems($childformitems, $formitem_id){
        //foreach($childformitems as $childformitem){
        for($i=0;$i<count($childformitems);$i++){
            if($childformitems[$i]['id']){
                $this->where('id', $childformitems[$i]['id'])
                    ->update([
                        'name'=>$childformitems[$i]['name'],
                        'order'=>$i+1,
                        'defaultflag'=>$childformitems[$i]['defaultflag']
                    ]);
            }
            else{
                $this->create([
                    'name'=>$childformitems[$i]['name'],
                    'formitem_id'=>$formitem_id,
                    'order'=>$i+1,
                    'defaultflag'=>$childformitems[$i]['defaultflag']
                ]);
            }
        }
    }
}
