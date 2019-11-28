<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formtag extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'formtag';

    public function formitems(){
    	return $this->hasMany('App\Models\Formitem','formtag_id','id');
    }

    public function inputtypes(){
    	return $this->hasMany('App\Models\Inputtype','formtag_id','id');
    }

    public function getFormtags(){
        return $this->with('inputtypes')->get();
    }
}
