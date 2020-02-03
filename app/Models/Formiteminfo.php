<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formiteminfo extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'formiteminfo';
    protected $fillable = ['info', 'delflag'];

    public function enrollinfo(){
        return $this->belongsTo('App\Models\Enrollinfo','enrollinfo_id');
    }

    public function formitem(){
        return $this->belongsTo('App\Models\Formitem','formitem_id');
    }
}
