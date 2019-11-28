<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inputtype extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'inputtype';

    public function formtag(){
        return $this->belongsTo('App\Models\Formtag','formtag_id');
    }
}
