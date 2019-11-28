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

    public function activities(){
        return $this->belongsTo('App\Models\Activities','activities_id');
    }

    public function formtag(){
        return $this->belongsTo('App\Models\Formtag','formtag_id');
    }

    public function childformitems(){
    	return $this->hasMany('App\Models\Childormitem','formitem_id','id');
    }
}
